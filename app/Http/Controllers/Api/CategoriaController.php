<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categoria\CategoriaCreatedRequest;
use App\Http\Requests\Categoria\CategoriaUpdatedRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{

    public function index()
    {
        return Categoria::all();
    }


    public function store(CategoriaCreatedRequest $request)
    {
        return Categoria::create($request->all());
    }


    public function show(Categoria $categoria)
    {
        return $categoria;
    }


    public function update(CategoriaUpdatedRequest $request, Categoria $categoria)
    {
         $categoria->update($request->all());
         return $categoria;
    }


    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return response()->noContent();
    }
}
