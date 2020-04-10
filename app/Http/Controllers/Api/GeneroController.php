<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genero;
use Illuminate\Http\Request;

class GeneroController extends Controller
{
    public function index()
    {
        return Genero::all();
    }

    public function store(Request $request)
    {
        return Genero::create($request->all());
    }

    public function show(Genero $genero)
    {
        return $genero;
    }

    public function update(Request $request, Genero $genero)
    {
        $genero->update($request->all());
        return $genero;
    }

    public function destroy(Genero $genero)
    {
        $genero->delete();
        return response()->noContent();
    }
}
