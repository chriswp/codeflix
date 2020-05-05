<?php

namespace Tests\Feature\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\Stubs\Controllers\CategoriaControllerStub;
use Tests\Stubs\Models\CategoriaStub;
use Tests\TestCase;

class BasicCrudControllerTest extends TestCase
{
    /** @var  CategoriaControllerStub */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        CategoriaStub::dropTable();
        CategoriaStub::createTable();
        $this->controller = new CategoriaControllerStub();
    }

    protected function tearDown(): void
    {
        CategoriaStub::dropTable();
        parent::tearDown();
    }

    public function testIndex()
    {
        $categoria = CategoriaStub::create(['nome' => 'categoria stub', 'descricao' => 'stub teste']);
        $resposta = $this->controller->index()->toArray();
        $this->assertEquals([$categoria->toArray()], $resposta);
    }


    public function testInvalidationData()
    {
        $this->expectException(ValidationException::class);
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['nome' => '']);
        $this->controller->store($request);
    }

    public function testStore()
    {
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['nome' => 'categoria 1', 'descricao' => 'descricao teste']);
        $res = $this->controller->store($request);

        $this->assertEquals(
            CategoriaStub::find(1)->toArray(),
            $res->toarray()
        );
    }


}
