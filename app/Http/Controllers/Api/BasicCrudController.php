<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

abstract class BasicCrudController
{
    protected abstract function model();

    protected abstract function requestValidationClass(): Request;

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
       $model = $this->model()::create($request->all());
       $model->refresh();
       return $model();
    }
}
