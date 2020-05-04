<?php


namespace App\Http\Controllers\Api;


use App\Http\Requests\Categoria\CategoriaCreatedRequest;

abstract class BasicCrudController
{
    protected abstract function model();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(CategoriaCreatedRequest $request)
    {
       $model = $this->model()::create($request->all());
       $model->refresh();
       return $model;
    }
}
