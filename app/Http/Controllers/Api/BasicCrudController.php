<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    protected abstract function model();

    protected abstract function rulesStore();

    protected abstract function rulesUpdate();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validationData = $this->validate($request, $this->rulesStore());
        $model = $this->model()::create($validationData);
        $model->refresh();
        return $model;
    }
}
