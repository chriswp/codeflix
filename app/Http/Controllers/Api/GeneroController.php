<?php

namespace App\Http\Controllers\Api;

use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'ativo' => 'boolean',
            'categorias_id' => 'required|array|exists:categorias,id,deleted_at,NULL',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'nome' => ['required', 'max:255', Rule::unique('generos')->ignore(request()->route('genero'))],
            'ativo' => 'boolean',
            'categorias_id' => 'required|array|exists:categorias,id,deleted_at,NULL',
        ];
    }

    public function store(Request $request)
    {
        $self = $this;
        $validationData = $this->validate($request, $this->rulesStore());
        $genero = DB::transaction(function () use ($validationData, $request, $self) {
            $genero = $this->model()::create($validationData);
            $self->handleRelations($genero, $request);
            return $genero;
        });
        $genero->refresh();

        return $genero;
    }

    public function update(Request $request, $id)
    {
        $self = $this;
        $genero = $this->firstOrFail($id);
        $validationData = $this->validate($request, $this->rulesUpdate());
        $genero = DB::transaction(function () use ($validationData, $request, $genero, $self) {
            $genero->update($validationData);
            $self->handleRelations($genero, $request);
            return $genero;
        });

        return $genero;
    }

    protected function handleRelations($genero, Request $request)
    {
        $genero->categorias()->sync($request->get('categorias_id'));
    }
}
