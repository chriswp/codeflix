<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Stubs\Controllers\CategoriaControllerStub;
use Tests\Stubs\Models\CategoriaStub;
use Tests\TestCase;
use Tests\Traits\SaveDataTest;
use Tests\Traits\ValidationsTest;

class BasicCrudControllerTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        CategoriaStub::dropTable();
        CategoriaStub::createTable();
    }

    protected function tearDown(): void
    {
        CategoriaStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        $categoria = CategoriaStub::create(['nome' => 'categoria stub', 'descricao' => 'stub teste']);
        $controller = new CategoriaControllerStub();
        $resposta = $controller->index()->toArray();
        $this->assertEquals([$categoria->toArray()],$resposta);
    }


}
