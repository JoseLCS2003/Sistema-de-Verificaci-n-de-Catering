<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class GenericCrudController extends Controller
{
    protected function getModel(string $modelName): ?Model
    {
        $class = 'App\\Models\\' . Str::studly($modelName);
        if (class_exists($class)) {
            return new $class;
        }
        abort(404, "Modelo '$modelName' no encontrado.");
    }

    public function index($model)
    {
        $modelInstance = $this->getModel($model);
        $data = $modelInstance::paginate(20);
        return response()->json($data);
    }

    public function show($model, $id)
    {
        $modelInstance = $this->getModel($model);
        $item = $modelInstance::findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request, $model)
    {
        $modelInstance = $this->getModel($model);
        $fillable = $modelInstance->getFillable();

        $data = $request->only($fillable);
        $item = $modelInstance::create($data);

        return response()->json(['message' => "$model creado correctamente", 'data' => $item]);
    }

    public function update(Request $request, $model, $id)
    {
        $modelInstance = $this->getModel($model);
        $item = $modelInstance::findOrFail($id);

        $fillable = $modelInstance->getFillable();
        $data = $request->only($fillable);
        $item->update($data);

        return response()->json(['message' => "$model actualizado correctamente", 'data' => $item]);
    }

    public function destroy($model, $id)
    {
        $modelInstance = $this->getModel($model);
        $item = $modelInstance::findOrFail($id);
        $item->delete();

        return response()->json(['message' => "$model eliminado correctamente"]);
    }
}
