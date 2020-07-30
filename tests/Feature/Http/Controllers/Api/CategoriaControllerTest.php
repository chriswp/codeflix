<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\SaveDataTest;
use Tests\Traits\ValidationsTest;

class CategoriaControllerTest extends TestCase
{
    use DatabaseMigrations, ValidationsTest, SaveDataTest;

    /** @var Categoria */
    private $categoria;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoria = factory(Categoria::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categorias.index'));
        $response
            ->assertStatus(200)
            ->assertJson([$this->categoria->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('categorias.show', ['categoria' => $this->categoria->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->categoria->toArray());
    }

    public function testInvalidacaoDados()
    {
        $dados = [
            'nome' => null
        ];
        $this->assertInValidationDataInStoreAction($dados,'required');
        $this->assertInValidationDataInUpdateAction($dados,'required');

        $dados = [
            'nome' => str_repeat('a', 256),
        ];
        $this->assertInValidationDataInStoreAction($dados, 'max.string', ['max' => 255]);
        $this->assertInValidationDataInUpdateAction($dados, 'max.string', ['max' => 255]);

        $dados = [
            'ativo' => 'a'
        ];
        $this->assertInValidationDataInStoreAction($dados, 'boolean');
        $this->assertInValidationDataInUpdateAction($dados, 'boolean');
    }

    public function testStore()
    {
        $dadosCategoria = [
            'nome' => 'categoria teste 2',
            'descricao' => 'descricao teste',
            'ativo' => false
        ];

        $response = $this->assertStore($dadosCategoria,
            array_merge($dadosCategoria, ['ativo' => false, 'descricao' => 'descricao teste']));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testStoreSomenteNomeInformado()
    {
        $dadosCategoria = [
            'nome' => 'categoria teste'
        ];
        $response = $this->assertStore($dadosCategoria,
            array_merge($dadosCategoria, ['ativo' => true, 'descricao' => null]));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testUpdate()
    {
        $dadosCategoria = [
            'nome' => 'categoria update',
            'ativo' => false,
            'descricao' => 'test'
        ];

        $response = $this->assertUpdate($dadosCategoria,
            array_merge($dadosCategoria,
                ['deleted_at' => null, 'ativo' => false, 'nome' => 'categoria update', 'descricao' => 'test']));
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testUpdateDescricaoNull()
    {
        $dadosCategoria = [
            'nome' => 'categoria update',
            'ativo' => false,
            'descricao' => ''
        ];
        $this->assertUpdate($dadosCategoria, array_merge($dadosCategoria, ['descricao' => null]));
    }

    public function testDelete()
    {
        $categoria = factory(Categoria::class)->create();
        $response = $this->json('DELETE', route('categorias.destroy', ['categoria' => "{$categoria->id}"]));
        $response->assertStatus(204);
        $this->assertNull(Categoria::find($categoria->id));
        $this->assertNotNull(Categoria::withTrashed()->find($categoria->id));
    }

    protected function model()
    {
        return Categoria::class;
    }

    protected function routeStore()
    {
        return route('categorias.store');
    }

    protected function routeUpdate()
    {
        return route('categorias.update', ['categoria' => $this->categoria->id]);
    }
}
