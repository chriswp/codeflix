<?php

namespace Tests\Feature\Models;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoriaTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate()
    {
        $dados = [
            'nome' => 'categoria',
            'descricao' => 'descricao apenas para teste',
            'ativo' => true
        ];
        $categoria = Categoria::create($dados);
        $this->assertEquals(1,$categoria->count());
        $this->assertInstanceOf(Categoria::class,$categoria);
        $this->assertDatabaseHas($categoria->getTable(),$dados);
    }

    public function testIndex()
    {
        $categorias = factory(Categoria::class,5)->create();;
        $this->assertEquals(5,$categorias->count());
        $this->assertInstanceOf(Collection::class,$categorias);
    }

    public function testUpdate()
    {
        $dados = [
            'nome' => 'categoria',
            'descricao' => 'descricao apenas para teste',
            'ativo' => true
        ];
        $categoria = factory(Categoria::class)->create();
        $categoria->update($dados);
        $this->assertInstanceOf(Categoria::class,$categoria);
        $this->assertDatabaseHas($categoria->getTable(),$dados);
    }

    public function testDelete()
    {
        $categoria = factory(Categoria::class)->create();
        $remover = $categoria->delete();
        $this->assertTrue($remover);
    }
}
