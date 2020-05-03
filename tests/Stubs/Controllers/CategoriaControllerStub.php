<?php


namespace Tests\Stubs\Controllers;


use App\Http\Controllers\Api\BasicCrudController;
use App\Http\Requests\Categoria\CategoriaCreatedRequest;
use Illuminate\Http\Request;
use Tests\Stubs\Models\CategoriaStub;

class CategoriaControllerStub extends BasicCrudController
{

    protected function model()
    {
        return CategoriaStub::class;
    }

    protected function requestValidationClass(): Request
    {
        return new CategoriaCreatedRequest();
    }

}
