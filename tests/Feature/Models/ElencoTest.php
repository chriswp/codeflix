<?php

namespace Tests\Feature\Models;

use App\Models\Elenco;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ElencoTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $dados = [
            'nome' => 'ator',
            'tipo' => Elenco::ATOR
        ];
        $elenco = Elenco::create($dados);
        $this->assertEquals(1,$elenco->count());
        $this->assertInstanceOf(Elenco::class,$elenco);
        $this->assertDatabaseHas($elenco->getTable(),$dados);
    }

    public function testIndex()
    {
        $elencos = factory(Elenco::class,5)->create();;
        $this->assertEquals(5,$elencos->count());
        $this->assertInstanceOf(Collection::class,$elencos);
    }

    public function testUpdate()
    {
        $dados = [
            'nome' => 'ator atualizado',
            'tipo' => Elenco::ATOR
        ];
        $elenco = factory(Elenco::class)->create();
        $elenco->update($dados);
        $this->assertInstanceOf(Elenco::class,$elenco);
        $this->assertDatabaseHas($elenco->getTable(),$dados);
    }

    public function testDelete()
    {
        $elenco = factory(Elenco::class)->create();
        $remover = $elenco->delete();
        $this->assertTrue($remover);
    }

    public function testValidateUuid()
    {
        $dados = [
            'nome' => 'diretor 1',
            'tipo' => Elenco::DIRETOR
        ];
        $elenco = Elenco::create($dados);
        $uuid = $elenco->id;
        $uuidValido = Uuid::isValid($uuid);
        $this->assertTrue($uuidValido);
    }
}
