<?php

namespace App\Http\Controllers\Api;

use App\Models\Categoria;

class CategoriaController extends BasicCrudController
{

    protected function model()
    {
       return Categoria::class;
    }

    protected function rulesStore()
    {
        return [
            'nome' => 'required|max:255|unique:categorias',
            'descricao' => 'nullable',
            'ativo' => 'boolean'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'nome' => 'required|max:255|unique:categorias,id,'.$this->get('id'),
            'ativo' => 'boolean'
        ];
    }

}
