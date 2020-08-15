<?php

namespace Tests\Feature\Models;

use App\Models\Categoria;
use App\Models\Genero;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use DatabaseMigrations;

    private $dadosTest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dadosTest = [
            'titulo' => 'titulo',
            'descricao' => 'descricao',
            'ano_lancamento' => 2010,
            'classificacao' => Video::CLASSIFICACOES[0],
            'duracao' => 90
        ];
    }

    public function testList()
    {
        factory(Video::class)->create();
        $videos = Video::All();
        $this->assertCount(1, $videos);
        $videosKeys = array_keys($videos->first()->getAttributes());
        $this->assertEqualsCanonicalizing(
            [
                'id',
                'titulo',
                'descricao',
                'ano_lancamento',
                'liberado',
                'classificacao',
                'duracao',
                'created_at',
                'updated_at',
                'deleted_at',
            ], $videosKeys);
    }

    public function testCreatedComCamposBasicos()
    {
        $video = Video::create($this->dadosTest);
        $video->refresh();

        $this->assertEquals(36, strlen($video->id));
        $this->assertFalse($video->liberado);
        $this->assertDatabaseHas('videos', $this->dadosTest + ['liberado' => false]);

        $video = Video::create($this->dadosTest + ['liberado' => true]);
        $this->assertTrue($video->liberado);
        $this->assertDatabaseHas('videos', $this->dadosTest + ['liberado' => true]);
    }

    public function testCreatedComRelacionamentos()
    {
        $categoria = factory(Categoria::class)->create();
        $genero = factory(Genero::class)->create();
        $video = Video::create($this->dadosTest + [
                'categorias_id' => [$categoria->id],
                'generos_id' => [$genero->id]
            ]);
        $this->assertHasCategoria($video->id, $categoria->id);
        $this->assertHasGenero($video->id, $genero->id);
    }

    public function testRollbackCreate()
    {
        try {
            Video::create([
                'titulo' => 'titulo',
                'descricao' => 'descricao',
                'ano_lancamento' => 2010,
                'classificacao' => Video::CLASSIFICACOES[0],
                'duracao' => 90,
                'categorias_id' => ['0', '1']
            ]);
            $hasError = false;
        } catch (QueryException $e) {
            $this->assertCount(0, Video::all());
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }

    public function testUpdatedComCamposBasicos()
    {
        $video = factory(Video::class)->create(['liberado' => false]);
        $video->update($this->dadosTest);
        $this->assertFalse($video->liberado);
        $this->assertDatabaseHas('videos', $this->dadosTest + ['liberado' => false]);

        $video = $video = factory(Video::class)->create(['liberado' => true]);
        $video->update($this->dadosTest);
        $this->assertTrue($video->liberado);
        $this->assertDatabaseHas('videos', $this->dadosTest + ['liberado' => true]);
    }

    public function testUpdatedComRelacionamentos()
    {
        $categoria = factory(Categoria::class)->create();
        $genero = factory(Genero::class)->create();
        $video = factory(Video::class)->create();
        $video->update($this->dadosTest + [
                'categorias_id' => [$categoria->id],
                'generos_id' => [$genero->id]
            ]);
        $this->assertHasCategoria($video->id, $categoria->id);
        $this->assertHasGenero($video->id, $genero->id);
    }


    public function testRollbackUpdate()
    {
        $hasError = false;
        try {
            $video = factory(Video::class)->create();
            $titulo = $video->titulo;
            $video->update([
                'titulo' => 'titulo',
                'descricao' => 'descricao',
                'ano_lancamento' => 2010,
                'classificacao' => Video::CLASSIFICACOES[0],
                'duracao' => 90,
                'categorias_id' => ['0', '1']
            ]);
        } catch (QueryException $e) {
            $this->assertDatabaseHas('videos', [
                'titulo' => $titulo
            ]);
            $hasError = true;
        }

        $this->assertTrue($hasError);
    }

    public function testHandleRelations()
    {
        $video = factory(Video::class)->create();
        Video::handleRelations($video, []);
        $this->assertCount(0, $video->categorias);
        $this->assertCount(0, $video->generos);

        $categoria = factory(Categoria::class)->create();
        Video::handleRelations($video, ['categorias_id' => [$categoria->id]]);
        $video->refresh();
        $this->assertCount(1, $video->categorias);

        $genero = factory(Genero::class)->create();
        Video::handleRelations($video, ['generos_id' => [$genero->id]]);
        $video->refresh();
        $this->assertCount(1, $video->generos);

        $video->categorias()->delete();
        $video->generos()->delete();

        Video::handleRelations($video, [
            'categorias_id' => [$categoria->id],
            'generos_id' => [$genero->id]
        ]);
        $video->refresh();
        $this->assertCount(1, $video->categorias);
        $this->assertCount(1, $video->generos);
    }


    public function testCreate()
    {
        $dados = [
            'titulo' => 'titulo',
            'descricao' => 'descricao',
            'ano_lancamento' => 2010,
            'classificacao' => Video::CLASSIFICACOES[0],
            'duracao' => 90
        ];

        $video = Video::create($dados);
        $this->assertEquals(1, $video->count());
        $this->assertInstanceOf(Video::class, $video);
        $this->assertDatabaseHas($video->getTable(), $dados);
    }

    public function testIndex()
    {
        $videos = factory(Video::class, 5)->create();;
        $this->assertEquals(5, $videos->count());
        $this->assertInstanceOf(Collection::class, $videos);
    }

    public function testUpdate()
    {
        $dados = [
            'titulo' => 'titulo update',
            'descricao' => 'descricao update',
            'ano_lancamento' => 2020,
            'classificacao' => Video::CLASSIFICACOES[1],
            'duracao' => 120
        ];
        $video = factory(Video::class)->create();
        $video->update($dados);
        $this->assertInstanceOf(Video::class, $video);
        $this->assertDatabaseHas($video->getTable(), $dados);
    }

    public function testDelete()
    {
        $video = factory(Video::class)->create();
        $remover = $video->delete();
        $this->assertTrue($remover);
    }

    public function testValidateUuid()
    {
        $dados = [
            'titulo' => 'titulo',
            'descricao' => 'descricao',
            'ano_lancamento' => 2010,
            'classificacao' => Video::CLASSIFICACOES[0],
            'duracao' => 90
        ];
        $video = Video::create($dados);
        $uuid = $video->id;
        $uuidValido = Uuid::isValid($uuid);
        $this->assertTrue($uuidValido);
    }

    public function testSyncCategories()
    {
        $categoriasId = factory(Categoria::class, 3)->create()->pluck('id')->toArray();
        $video = factory(Video::class)->create();
        Video::handleRelations($video, [
            'categorias_id' => [$categoriasId[0]]
        ]);
        $this->assertDatabaseHas('categoria_video', [
            'video_id' => $video->id,
            'categoria_id' => $categoriasId[0],
        ]);

        Video::handleRelations($video, [
            'categorias_id' => [$categoriasId[1],$categoriasId[2]]
        ]);
        $this->assertDatabaseMissing('categoria_video', [
            'categoria_id' => $categoriasId[0],
            'video_id' => $video->id,
        ]);
        $this->assertDatabaseHas('categoria_video', [
            'video_id' => $video->id,
            'categoria_id' => $categoriasId[1],
        ]);
        $this->assertDatabaseHas('categoria_video', [
            'video_id' => $video->id,
            'categoria_id' => $categoriasId[2],
        ]);
    }

    public function testSyncGenres()
    {
        $generosId = factory(Genero::class, 3)->create()->pluck('id')->toArray();
        $video = factory(Video::class)->create();
        Video::handleRelations($video, [
            'generos_id' => [$generosId[0]]
        ]);
        $this->assertDatabaseHas('genero_video', [
            'video_id' => $video->id,
            'genero_id' => $generosId[0],
        ]);

        Video::handleRelations($video, [
            'generos_id' => [$generosId[1],$generosId[2]]
        ]);
        $this->assertDatabaseMissing('genero_video', [
            'genero_id' => $generosId[0],
            'video_id' => $video->id,
        ]);
        $this->assertDatabaseHas('genero_video', [
            'video_id' => $video->id,
            'genero_id' => $generosId[1],
        ]);
        $this->assertDatabaseHas('genero_video', [
            'video_id' => $video->id,
            'genero_id' => $generosId[2],
        ]);
    }

    protected function assertHasCategoria($videoId, $categoriaId)
    {
        $this->assertDatabaseHas('categoria_video', [
            'video_id' => $videoId,
            'categoria_id' => $categoriaId,
        ]);
    }

    protected function assertHasGenero($videoId, $generoId)
    {
        $this->assertDatabaseHas('genero_video', [
            'video_id' => $videoId,
            'genero_id' => $generoId,
        ]);
    }
}
