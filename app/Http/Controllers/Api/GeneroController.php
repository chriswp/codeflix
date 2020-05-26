<?php

namespace App\Http\Controllers\Api;

use App\Models\Genero;
use Illuminate\Validation\Rule;

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
            'nome' => ['required', 'max:255', Rule::unique('generos')->ignore(request()->route('genero'))],
            'ativo' => 'boolean'
        ];
    }
}
