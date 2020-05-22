<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Controllers\Api\BasicCrudController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function testInvalidationDataInStoreAction()
    {
        $this->expectException(ValidationException::class);
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['nome' => '']);
        $this->controller->store($request);
    }

    public function testInvalidationDataInUpdateAction()
    {
        $this->expectException(ValidationException::class);
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['nome' => '']);
        $categoria = CategoriaStub::create(['nome' => 'categoria stub', 'descricao' => 'stub teste']);
        $this->controller->update($request, $categoria->id);
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
            $res->toArray()
        );
    }

    public function testIfFindOrFailFecthModel()
    {
        $categoria = CategoriaStub::create(['nome' => 'categoria reflection', 'descricao' => 'reflection teste']);
        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('firstOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [$categoria->id]);
        $this->assertInstanceOf(CategoriaStub::class, $result);
    }

    public function testIfFindOrFailThrowExceptionWhenIdInvalid()
    {
        $this->expectException(ModelNotFoundException::class);
        $reflectionClass = new \ReflectionClass(BasicCrudController::class);
        $reflectionMethod = $reflectionClass->getMethod('firstOrFail');
        $reflectionMethod->setAccessible(true);

        $result = $reflectionMethod->invokeArgs($this->controller, [0]);
        $this->assertInstanceOf(CategoriaStub::class, $result);
    }

    public function testShow()
    {
        $categoria = CategoriaStub::create(['nome' => 'categoria reflection', 'descricao' => 'reflection teste']);
        $result = $this->controller->show($categoria->id);
        $this->assertEquals($result->toArray(), CategoriaStub::find(1)->toArray());
    }

    public function testUpdate()
    {
        $categoria = CategoriaStub::create(['nome' => 'categoria update', 'descricao' => 'reflection update']);
        $request = \Mockery::mock(Request::class);
        $request
            ->shouldReceive('all')
            ->once()
            ->andReturn(['nome' => 'categoria 1', 'descricao' => 'descricao teste']);
        $res = $this->controller->update($request, $categoria->id);

        $this->assertEquals(
            CategoriaStub::find(1)->toArray(),
            $res->toarray()
        );
    }

    public function testDestroy()
    {
        $categoria = CategoriaStub::create(['nome' => 'categoria update', 'descricao' => 'reflection update']);
        $response = $this->controller->destroy($categoria->id);
        $this->createTestResponse($response)
            ->assertStatus(204);
        $this->assertCount(0, CategoriaStub::all());
    }


}
