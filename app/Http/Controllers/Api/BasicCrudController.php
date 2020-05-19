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

    protected function firstOrFail($id)
    {
        $model = $this->model();
        $keyName = (new $model)->getRouteKeyName();
        return $this->model()::where($keyName, $id)->firstOrFail();
    }

    public function show($id)
    {
        $obj = $this->firstOrFail($id);
        return $obj;
    }

    public function update(Request $request, $id)
    {
        $obj = $this->firstOrFail($id);
        $validationData = $this->validate($request, $this->rulesUpdate());
        $obj->update($validationData);
        return $obj;
    }

    public function destroy($id)
    {
        $obj = $this->firstOrFail($id);
        $obj->delete();
        return response()->noContent();
    }
}
