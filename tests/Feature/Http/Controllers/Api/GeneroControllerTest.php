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
        $response = $this->get(route('generos.show',['genero' => $genero->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genero->toArray());
    }
}
