<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\GeneroController;
use App\Models\Categoria;
use App\Models\Genero;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Exception\TestExcepiton;
use Tests\TestCase;
use Tests\Traits\SaveDataTest;
use Tests\Traits\ValidationsTest;
use Illuminate\Http\Request;

class GeneroControllerTest extends TestCase
{
    use DatabaseMigrations, SaveDataTest, ValidationsTest;

    /** @var Genero */
    private $genero;

    protected function setUp(): void
    {
        parent::setUp();
        $this->genero = factory(Genero::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('generos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genero->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('generos.show', ['genero' => $this->genero->id]));
        $response
            ->assertStatus(200)
            ->assertJson($this->genero->toArray());
    }

    public function testStore()
    {
        $categoria = factory(Categoria::class)->create();

        $dadosGenero = ['nome' => 'genero 1'];
        $this->assertStore($dadosGenero + ['categorias_id' => [$categoria->id]],
            $dadosGenero + ['ativo' => true, 'deleted_at' => null]);

        $dadosGenero = ['nome' => 'genero 2', 'ativo' => false];
        $response = $this->assertStore($dadosGenero + ['categorias_id' => [$categoria->id]],
            $dadosGenero + ['ativo' => false, 'deleted_at' => null]);
        $response->assertJsonStructure(['created_at', 'updated_at']);
        $this->assertHasCategory($response->json('id'),$categoria->id);
    }

    public function assertHasCategory($generoId,$categoriaId)
    {
        $this->assertDatabaseHas('categoria_genero',[
            'genero_id' => $generoId,
            'categoria_id' => $categoriaId,
        ]);
    }

    public function testSyncCategories()
    {
        $categoriasId = factory(Categoria::class,3)->create()->pluck('id')->toArray();
        $dados = [
            'nome' => 'genero teste',
            'categorias_id' => [$categoriasId[0]]
        ];
        $response = $this->json('POST',$this->routeStore(),$dados);
        $this->assertDatabaseHas('categoria_genero',[
            'genero_id' => $categoriasId[0],
            'categoria_id' => $response->json('id'),
        ]);

        $dados = [
            'nome' => 'genero teste 2',
            'categorias_id' => [$categoriasId[1],$categoriasId[2]]
        ];
        $response = $this->json('PUT',$this->routeStore(),$dados);
        $this->assertDatabaseHas('categoria_genero',[
            'genero_id' => $categoriasId[1],
            'categoria_id' => $response->json('id'),
        ]);
        $this->assertDatabaseHas('categoria_genero',[
            'genero_id' => $categoriasId[2],
            'categoria_id' => $response->json('id'),
        ]);
    }

    public function testInvalidationData()
    {
        $dados = ['nome' => '', 'categorias_id' => ''];
        $this->assertInvalidationDataInStoreAction($dados, 'required');
        $this->assertInvalidationDataInUpdateAction($dados, 'required');

        $dados = ['nome' => str_repeat('a', 256)];
        $this->assertInvalidationDataInStoreAction($dados, 'max.string', ['max' => 255]);
        $this->assertInvalidationDataInUpdateAction($dados, 'max.string', ['max' => 255]);

        $dados = ['ativo' => 'true'];
        $this->assertInvalidationDataInStoreAction($dados, 'boolean');
        $this->assertInvalidationDataInUpdateAction($dados, 'boolean');

        $dados = ['categorias_id' => 'a'];
        $this->assertInvalidationDataInStoreAction($dados, 'array');
        $this->assertInvalidationDataInUpdateAction($dados, 'array');

        $dados = ['categorias_id' => ['07cdddcc-e0cc-4856-aae3-d15b1f3a886e']];
        $this->assertInvalidationDataInStoreAction($dados, 'exists');
        $this->assertInvalidationDataInUpdateAction($dados, 'exists');

        $categoria = factory(Categoria::class)->create();
        $categoria->delete();
        $dados = ['categorias_id' => [$categoria->id]];
        $this->assertInvalidationDataInStoreAction($dados, 'exists');
        $this->assertInvalidationDataInUpdateAction($dados, 'exists');

        $dados = $this->genero->only('nome');
        $this->assertInvalidationDataInStoreAction($dados, 'unique');

        $dadosUpdate = $dados;
        $this->genero = factory(Genero::class)->create();
        $this->assertInvalidationDataInUpdateAction($dadosUpdate, 'unique');
    }

    public function testStoreCampoAtivoNaoInformado()
    {
        $categoria = factory(Categoria::class)->create();
        $dadoGenero = ['nome' => 'genero 1'];
        $response = $this->assertStore($dadoGenero + ['categorias_id' => [$categoria->id]],
            array_merge($dadoGenero, ['ativo' => true]));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testUpdate()
    {
        $categoria = factory(Categoria::class)->create();
        $genero = [
            'nome' => 'genero update',
            'ativo' => false,
        ];

        $this->assertUpdate($genero + ['categorias_id' => [$categoria->id]],
            array_merge($genero, ['deleted_at' => null, 'nome' => 'genero update', 'ativo' => false]));
    }

    public function testDelete()
    {
        $genero = factory(Genero::class, 1)->create()->first();
        $response = $this->json('DELETE', route('generos.destroy', ['genero' => "{$this->genero->id}"]));
        $response->assertStatus(204);
        $this->assertNull(Genero::find($this->genero->id));
        $this->assertNotNull(Genero::withTrashed()->find($genero->id));
    }

    public function testRollbackStore()
    {
        $controller = \Mockery::mock(GeneroController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn($this->genero->toArray());

        $controller->shouldReceive('rulesStore')
            ->withAnyArgs()
            ->andReturn([]);

        $controller->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestExcepiton());

        $request = \Mockery::mock(Request::class);
        $hasError = false;
        try {
            $controller->store($request);
        } catch (TestExcepiton $e) {
            $this->assertCount(1, Genero::all());
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }

    public function testRollbackUpdate()
    {
        $controller = \Mockery::mock(GeneroController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->shouldReceive('findOrFail')
            ->withAnyArgs()
            ->andReturn($this->genero);

        $controller->shouldReceive('validate')
            ->withAnyArgs()
            ->andReturn(['nome' => 'genero teste']);

        $controller->shouldReceive('rulesUpdate')
            ->withAnyArgs()
            ->andReturn([]);

        $controller->shouldReceive('handleRelations')
            ->once()
            ->andThrow(new TestExcepiton());

        $request = \Mockery::mock(Request::class);
        $hasError = false;
        try {
            $controller->update($request, $this->genero->id);
        } catch (TestExcepiton $e) {
            $this->assertCount(1, Genero::all());
            $hasError = true;
        }
        $this->assertTrue($hasError);
    }



    protected function model()
    {
        return Genero::class;
    }

    protected function routeStore()
    {
        return route('generos.store');
    }

    protected function routeUpdate()
    {
        return route('generos.update', ['genero' => "{$this->genero->id}"]);
    }
}
