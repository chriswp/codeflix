<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoriaControllerTest extends TestCase
{
   use DatabaseMigrations;

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
        $response = $this->get(route('categorias.show',['categoria' => $categoria->id]));

        $response
            ->assertStatus(200)
            ->assertJson($categoria->toArray());
    }
}
