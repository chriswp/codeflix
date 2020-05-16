<?php

namespace App\Http\Controllers\Api;

use App\Models\Genero;

class GeneroController extends BasicCrudController
{

    protected function model()
    {
        return Genero::class;
    }

    protected function rulesStore()
    {
        return [
            'nome' => 'required|max:255|unique:generos',
            'ativo' => 'boolean'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'nome' => 'required|max:255|unique:generos,id,'.$this->get('id'),
            'ativo' => 'boolean'
        ];
    }
}
