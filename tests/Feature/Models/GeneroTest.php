<?php

namespace Tests\Feature\Models;

use App\Models\Genero;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class GeneroTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $dados = [
            'nome' => 'genero',
            'ativo' => true
        ];
        $genero = Genero::create($dados);
        $this->assertEquals(1,$genero->count());
        $this->assertInstanceOf(Genero::class,$genero);
        $this->assertDatabaseHas($genero->getTable(),$dados);
    }

    public function testIndex()
    {
        $generos = factory(Genero::class,5)->create();;
        $this->assertEquals(5,$generos->count());
        $this->assertInstanceOf(Collection::class,$generos);
    }

    public function testUpdate()
    {
        $dados = [
            'nome' => 'genero',
            'ativo' => true
        ];
        $genero = factory(Genero::class)->create();
        $genero->update($dados);
        $this->assertInstanceOf(Genero::class,$genero);
        $this->assertDatabaseHas($genero->getTable(),$dados);
    }

    public function testDelete()
    {
        $genero = factory(Genero::class)->create();
        $remover = $genero->delete();
        $this->assertTrue($remover);
    }

    public function testValidateUuid()
    {
        $dados = [
            'nome' => 'genero',
            'ativo' => true
        ];
        $genero = Genero::create($dados);
        $uuid = $genero->id;
        $uuidValido = Uuid::isValid($uuid);
        $this->assertTrue($uuidValido);
    }
}
