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
        $dadosGenero = ['nome' => 'genero 1', 'ativo' => false];
        $response = $this->assertStore($dadosGenero + ['categorias_id' => [$categoria->id]],
            array_merge($dadosGenero, ['ativo' => false, 'nome' => 'genero 1']));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testInvalidationData()
    {
        $dados = ['nome' => null];
        $this->assertInvalidationDataInStoreAction($dados, 'required');
        $this->assertInvalidationDataInUpdateAction($dados, 'required');

        $dados = ['nome' => str_repeat('a', 256)];
        $this->assertInvalidationDataInStoreAction($dados, 'max.string', ['max' => 255]);
        $this->assertInvalidationDataInUpdateAction($dados, 'max.string', ['max' => 255]);

        $dados = ['ativo' => 'true'];
        $this->assertInvalidationDataInStoreAction($dados, 'boolean');
        $this->assertInvalidationDataInUpdateAction($dados, 'boolean');

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
        try {
            $controller->store($request);
        } catch (TestExcepiton $e) {
            $this->assertCount(1, Genero::all());
        }
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
