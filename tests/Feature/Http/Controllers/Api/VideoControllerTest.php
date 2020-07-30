<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\VideoController;
use App\Models\Categoria;
use App\Models\Genero;
use App\Models\Video;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\Exception\TestExcepiton;
use Tests\TestCase;
use Tests\Traits\SaveDataTest;
use Tests\Traits\ValidationsTest;

class VideoControllerTest extends TestCase
{
    use DatabaseMigrations, SaveDataTest, ValidationsTest;

    /** @var Video */
    private $video;

    /** @var array */
    private $dadosVideoTest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory(Video::class)->create([
            'liberado' => false
        ]);

        $this->dadosVideoTest = [
            'titulo' => 'titulo',
            'descricao' => 'descricao',
            'ano_lancamento' => 2010,
            'classificacao' => Video::CLASSIFICACOES[0],
            'duracao' => 90
        ];
    }

    public function testIndex()
    {
        $response = $this->get(route('videos.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->video->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('videos.show', ['video' => $this->video->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->video->toArray());
    }

    public function testSave()
    {
        $categoria = factory(Categoria::class)->create();
        $genero = factory(Genero::class)->create();
        $response = $this->assertStore($this->dadosVideoTest + [
                'categorias_id' => [$categoria->id],
                'generos_id' => [$genero->id]
            ],
            array_merge($this->dadosVideoTest, ['liberado' => false]));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testRollbackStore()
    {
        $controller = \Mockery::mock(VideoController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn($this->dadosVideoTest);

        $controller->shouldReceive('rulesStore')
            ->withAnyArgs()
            ->andReturn([]);

        $controller->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestExcepiton());

        $request = \Mockery::mock(Request::class);
        try {
            $controller->store($request);
        } catch (TestExcepiton $e) {
            $this->assertCount(1,Video::all());
        }
    }


    public function testInvalidationRequired()
    {
        $dados = [
            'titulo' => '',
            'descricao' => '',
            'ano_lancamento' => '',
            'classificacao' => '',
            'duracao' => '',
            'categorias_id' => '',
            'generos_id' => '',
        ];

        $this->assertInvalidationDataInStoreAction($dados, 'required');
        $this->assertInvalidationDataInUpdateAction($dados, 'required');
    }

    public function testInvalidationInteger()
    {
        $dados = [
            'ano_lancamento' => 'aa',
            'duracao' => 'aa'
        ];

        $this->assertInvalidationDataInStoreAction($dados, 'integer');
        $this->assertInvalidationDataInUpdateAction($dados, 'integer');
    }

    public function testInvalidationIn()
    {
        $dados = [
            'classificacao' => 'aa'
        ];

        $this->assertInvalidationDataInStoreAction($dados, 'in');
        $this->assertInvalidationDataInUpdateAction($dados, 'in');
    }

    public function testInvalidationArray()
    {
        $dados = [
            'categorias_id' => 1,
            'generos_id' => 2,
        ];

        $this->assertInvalidationDataInStoreAction($dados, 'array');
        $this->assertInvalidationDataInUpdateAction($dados, 'array');
    }

    public function testInvalidationExists()
    {
        $dados = [
            'categorias_id' => ['07cdddcc-e0cc-4856-aae3-d15b1f3a886e'],
            'generos_id' => ['07cdddcc-e0cc-4856-aae3-d15b1f3a886e']
        ];

        $this->assertInvalidationDataInStoreAction($dados, 'exists');
        $this->assertInvalidationDataInUpdateAction($dados, 'exists');
    }

    public function testInvalidationDateFormat()
    {
        $dados['ano_lancamento'] = 'aa';

        $this->assertInvalidationDataInStoreAction($dados, 'date_format',['format' => 'Y']);
        $this->assertInvalidationDataInUpdateAction($dados, 'date_format',['format' => 'Y']);
    }

    public function testInvalidationLiberadoField()
    {
        $dados = [
           'liberado' => 'aa'
        ];

        $this->assertInvalidationDataInStoreAction($dados, 'boolean');
        $this->assertInvalidationDataInUpdateAction($dados, 'boolean');
    }


    public function testDelete()
    {
        $video = factory(video::class, 1)->create()->first();
        $response = $this->json('DELETE', route('videos.destroy', ['video' => "{$this->video->id}"]));
        $response->assertStatus(204);
        $this->assertNull(video::find($this->video->id));
        $this->assertNotNull(video::withTrashed()->find($video->id));
    }

    protected function model()
    {
        return video::class;
    }

    protected function routeStore()
    {
        return route('videos.store');
    }

    protected function routeUpdate()
    {
        return route('videos.update', ['video' => "{$this->video->id}"]);
    }
}
