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

    public function testStoreValidation()
    {
        $response = $this->json('POST', route('categorias.store'), []);
        $this->assertValidationData($response, ['nome'], 'required');
        $response->assertJsonMissingValidationErrors(['ativo']);

        $response = $this->json('POST', route('categorias.store'), [
            'nome' => str_repeat('a', 256),
            'ativo' => 'a'
        ]);

        $this->assertValidationData($response, ['nome'], 'max.string', ['max' => 255]);
        $this->assertValidationData($response, ['ativo'], 'boolean');
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
        $dadosCategoria['descricao'] = '';
        $this->assertUpdate($dadosCategoria, array_merge($dadosCategoria, ['descricao' => null]));
    }

    public function testDelete()
    {
        $response = $this->json('DELETE', route('categorias.destroy', ['categoria' => "{$this->categoria->id}"]));
        $response->assertStatus(204);
        $this->assertNull(Categoria::find($this->categoria->id));
        $this->assertNotNull(Categoria::withTrashed()->find($this->categoria->id));
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
