<?php

namespace App\Http\Controllers;

use App\Form\FormValidation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;

abstract class ControllerBase extends Controller
{
    protected $model;

    private $rules = [
        'nome' => 'required',
        'ano' => 'required',
        'file_path' => 'required|image'
    ];

    public function index(Request $request)
    {
        try {
            $models = $this->model::all();
            // Adicionando o caminho completo da imagem à cada this-model
            foreach ($models as $model) {
                $model->file_path = url(str_replace('/public/', '/',"storage/$model->file_path"));
            }
            return response()->json($models);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $model = $this->model::find($id);
            $model->file_path = url(str_replace('/public/', '/',"storage/$model->file_path"));
            return response()->json($model);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            //validação dos inputs
            $validation = FormValidation::validar($request->all(), $this->rules);

            if ($validation !== true) {
                return $validation;
            }

            $nome = $request->get('nome');
            $ano = $request->get('ano');
            $file_path = $request->file('file_path')->store('public');

            $model = new $this->model();
            $model->nome = $nome;
            $model->ano = $ano;
            $model->file_path = $file_path;

            $model->save();
            DB::commit();
            return response()->json(['message' => 'Oba, deu certo!'], 201);

        } catch (\Couchbase\QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        //validação dos inputs
        $validation = FormValidation::validar($request->all(), $this->rules);

        if ($validation !== true) {
            return $validation;
        }

        try {
            $nome = $request->get('nome');
            $ano = $request->get('ano');
            $file_path = $request->file('file_path')->store('public');

            $model = $this->model::find($id);

            if (!$model) {
                return response()->json(['errors' => 'recurso não encontrado', 404]);
            }

            $model->nome = $nome;
            $model->ano = $ano;
            $model->file_path = $file_path;

            $model->save();

            DB::commit();
            return response()->json(['message' => 'Oba, deu certo!'], 200);

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Desculpe, algo deu errado'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = $this->model::find($id);
            if (!$model) {
                return response()->json(['message' => 'Recurso não encontrado'], 401);
            }

            $model->delete();

            DB::commit();
            return response()->json(['message' => 'recurso deletado com sucesso'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Desculpe, algo deu errado!'], 500);
        }
    }
}
