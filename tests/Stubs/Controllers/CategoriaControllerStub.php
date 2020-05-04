<?php


namespace Tests\Stubs\Controllers;


use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stubs\Models\CategoriaStub;

class CategoriaControllerStub extends BasicCrudController
{

    protected function model()
    {
        return CategoriaStub::class;
    }


}
