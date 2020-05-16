<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Elenco;
use Illuminate\Http\Request;

class ElencoController extends BasicCrudController
{
    //
    protected function model()
    {
       return Elenco::class;
    }

    protected function rulesStore()
    {
        return [
            'nome' => 'required|unique:elencos',
            'tipo' => 'required|numeric|in:1,2'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'nome' => 'required|unique:elencos,id,'.$this->get('id'),
            'tipo' => 'required|numeric|in:1,2'
        ];
    }
}
