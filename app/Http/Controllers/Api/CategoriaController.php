<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    public function index()
    {
        return Categoria::all();
    }


    public function store(CategoriaCreatedRequest $request)
    {
        $categoria = Categoria::create($request->all());
        $categoria->refresh();
        return $categoria;
    }


    public function show(Categoria $categoria)
    {
        return $categoria;
    }


    public function update(Request $request, Categoria $categoria)
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
