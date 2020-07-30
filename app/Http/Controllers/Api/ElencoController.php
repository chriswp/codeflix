<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Elenco;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ElencoController extends BasicCrudController
{

    protected function model()
    {
        return Elenco::class;
    }

    protected function rulesStore()
    {
        return [
            'nome' => 'required|max:255|unique:elencos',
            'tipo' => 'required|numeric|in:1,2'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'nome' => ['required', 'max:255', Rule::unique('elencos')->ignore(request()->route('elenco'))],
            'tipo' => 'required|numeric|in:1,2'
        ];
    }
}
