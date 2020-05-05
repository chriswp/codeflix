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

    protected function rulesStore()
    {
        return [
            'nome' => 'required|max:255|unique:categoria_stubs',
            'description' => 'nullable'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'nome' => 'required|max:255',
            'description' => 'nullable'
        ];
    }
}
