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

    //definindo as regras de validação
    private $rules = [
        'nome' => 'required',
        'ano' => 'required',
        'file_path' => 'required|image'
    ];

    public function index()
    {
        // o $this->model representa a classe que herdará esse ControllerBase
        try {
            $models = $this->model::all();
            // Adicionando o caminho completo da imagem à cada objeto, trocando a uri '/public/' por '/'
            foreach ($models as $model) {
                $model->file_path = url(str_replace('/public/', '/',"storage/$model->file_path"));
            }
            return response()->json($models);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function show($id)
    {
        try {
            $model = $this->model::find($id);

            // Adicionando o caminho completo da imagem desse objeto, trocando a uri '/public/' por '/'
            $model->file_path = url(str_replace('/public/', '/',"storage/$model->file_path"));
            return response()->json($model);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function store(Request $request)
    {
        //criando um ponto de restauração
        DB::beginTransaction();

        try {
            //validação dos inputs
            $validation = FormValidation::validar($request->all(), $this->rules);

            if ($validation !== true) {
                return $validation;
            }

            $nome = $request->get('nome');
            $ano = $request->get('ano');

            //verifica se existe um arquivo no $request cuja chave é 'file_path', se existir, salvará no caminho 'public'
            $file_path = $request->file('file_path')->store('public');

            $model = new $this->model();
            $model->nome = $nome;
            $model->ano = $ano;
            $model->file_path = $file_path;

            $model->save();
            //confirma a transação para o banco de dados
            DB::commit();
            return response()->json(['message' => 'Oba, deu certo!'], 201);

        } catch (\Couchbase\QueryException $e) {
            //restaurará o banco de dados para o ponto de restauração criado antes dessa operação
            DB::rollBack();

            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function update(Request $request, $id)
    {
        //criando um ponto de restauração
        DB::beginTransaction();

        try {
            //validação dos inputs
            $validation = FormValidation::validar($request->all(), $this->rules);

            if ($validation !== true) {
                return $validation;
            }
            $model = $this->model::find($id);

            $nome = $request->get('nome');
            $ano = $request->get('ano');

            //verifica se existe um arquivo no $request cuja chave é 'file_path', se existir, salvará no caminho 'public'
            $file_path = $request->file('file_path')->store('public');

            $model->nome = $nome;
            $model->ano = $ano;
            $model->file_path = $file_path;

            $model->save();
            //confirma a transação para o banco de dados
            DB::commit();
            return response()->json(['message' => 'Oba, deu certo!'], 201);

        } catch (QueryException $e) {
            //restaurará o banco de dados para o ponto de restauração criado antes dessa operação
            DB::rollBack();

            return response()->json(['message' => 'Desculpe, algo deu errado'], 500);
        }
    }

    public function destroy($id)
    {
        //criando um ponto de restauração
        DB::beginTransaction();

        try {
            $model = $this->model::find($id);

            //verificando se o objeto existe no banco
            if (!$model) {
                return response()->json(['message' => 'Recurso não encontrado'], 401);
            }

            $model->delete();

            //confirma a transação para o banco de dados
            DB::commit();
            return response()->json(['message' => 'recurso deletado com sucesso'], 200);
        } catch (QueryException $e) {
            //restaurará o banco de dados para o ponto de restauração criado antes dessa operação
            DB::rollBack();

            return response()->json(['message' => 'Desculpe, algo deu errado!'], 500);
        }
    }
}
