<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\ValidationsTest;

class CategoriaControllerTest extends TestCase
{
    use DatabaseMigrations, ValidationsTest;

    public function testIndex()
    {
        $categoria = factory(Categoria::class)->create();
        $response = $this->get(route('categorias.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$categoria->toArray()]);
    }

    public function testShow()
    {
        $categoria = factory(Categoria::class)->create();
        $response = $this->get(route('categorias.show', ['categoria' => $categoria->id]));

        $response
            ->assertStatus(200)
            ->assertJson($categoria->toArray());
    }

    public function testStoreValidation()
    {
        $response = $this->json('POST', route('categorias.store'), []);
        $this->assertValidationData($response,['nome'],'required');
        $response->assertJsonMissingValidationErrors(['ativo']);

        $response = $this->json('POST', route('categorias.store'), [
            'nome' => str_repeat('a',256),
            'ativo' => 'a'
        ]);

        $this->assertValidationData($response,['nome'],'max.string',['max' => 255]);
        $this->assertValidationData($response,['ativo'],'boolean');
    }

    public function testStore()
    {
        //valida se ao passar somente os nomes, os dados foram salvos corretamente
        //por padrão o campo ativo, caso não informado, deve ser "true"
        $response = $this->json('POST', route('categorias.store'), [
            'nome' => 'categoria teste'
        ]);

        $id = $response->json('id');
        $categoria = Categoria::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($categoria->toArray());

        $this->assertTrue($response->json('ativo'));
        $this->assertNull($response->json('descricao'));

        //valida se os dados passando foram salvos corretamente
        $response = $this->json('POST', route('categorias.store'), [
            'nome' => 'categoria teste 2',
            'descricao' => 'descricao',
            'ativo' => false
        ]);

        $response->assertJsonFragment([
            'ativo' => false,
            'descricao' => 'descricao',
        ]);
    }

    public function testUpdate()
    {
        //validando se os campos sao alterados corretamente
        $categoria = factory(Categoria::class)->create([
            'ativo' => false,
            'descricao' => 'descricao'
        ]);

        $response = $this->json('PUT', route('categorias.update', ['categoria' => "{$categoria->id}"]), [
            'nome' => 'categoria update',
            'ativo' => true,
            'descricao' => 'test'
        ]);

        $id = $response->json('id');
        $categoria = Categoria::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($categoria->toArray())
            ->assertJsonFragment([
                'ativo' => true,
                'descricao' => 'test'
            ]);


        //validando se ao passar uma string vazia no campo descricao, se vai ser válido
        $response = $this->json('PUT', route('categorias.update', ['categoria' => "{$categoria->id}"]), [
            'descricao' => ''
        ]);
        $this->assertNull($response->json('descricao'));
    }

    public function testDelete()
    {
        $categoria = factory(Categoria::class)->create()->first();
        $response = $this->json('DELETE', route('categorias.destroy', ['categoria' => "{$categoria->id}"]));
        $response->assertStatus(204);
        $this->assertNull(Categoria::find($categoria->id));
        $this->assertNotNull(Categoria::withTrashed()->find($categoria->id));
    }
}
