<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genero;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GeneroControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $genero = factory(Genero::class)->create();
        $response = $this->get(route('generos.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$genero->toArray()]);
    }

    public function testShow()
    {
        $genero = factory(Genero::class)->create();
        $response = $this->get(route('generos.show', ['genero' => $genero->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genero->toArray());
    }

    public function testUpdate()
    {
        //validando se os campos sao alterados corretamente
        $genero = factory(Genero::class)->create([
            'ativo' => false,
            'nome' => 'genero'
        ]);

        $response = $this->json('PUT', route('generos.update', ['genero' => "{$genero->id}"]), [
            'nome' => 'genero update',
            'ativo' => true
        ]);

        $id = $response->json('id');
        $genero = Genero::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genero->toArray())
            ->assertJsonFragment([
                'ativo' => true,
                'nome' => 'genero update'
            ]);
    }

    public function testDelete()
    {
        $genero = factory(Genero::class, 1)->create()->first();
        $response = $this->json('DELETE', route('generos.destroy', ['genero' => "{$genero->id}"]));
        $response->assertStatus(204);
        $this->assertNull(Genero::find($genero->id));
        $this->assertNotNull(Genero::withTrashed()->find($genero->id));
    }
}
