<?php

namespace App\Http\Controllers\Api;

use App\Models\Categoria;
use Illuminate\Validation\Rule;

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
            'nome' => ['required','max:255',Rule::unique('categorias')->ignore(request()->route('categoria'))],
            'ativo' => 'boolean',
            'descricao' => 'nullable'
        ];
    }

}
