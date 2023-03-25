<?php

namespace App\Http\Controllers;


use App\Form\FormValidation;
use App\Models\Carro;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller;

class CarroController extends Controller
{
    private $request;

    private $rules = [
        'nome' => 'required',
        'ano' => 'required',
        'file_path' => 'required|image'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        try {
            $carros = Carro::all();
            return response()->json($carros);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function show($id)
    {
        try {
            $carro = Carro::find($id);

            return response()->json($carro);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            //validação dos inputs
            $validation = FormValidation::validar($this->request->all(), $this->rules);

            if ($validation !== true) {
                return $validation;
            }

            $nome = $this->request->get('nome');
            $ano = $this->request->get('ano');
            $file_path = $this->request->file('file_path')->store('public');

            $carro = new Carro();
            $carro->nome = $nome;
            $carro->ano = $ano;
            $carro->file_path = $file_path;

            $carro->save();
            DB::commit();
            return response()->json(['message' => 'Oba, deu certo!'], 201);

        } catch (\Couchbase\QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Desculpe, algo deu errado']);
        }
    }

    public function update($id)
    {
        DB::beginTransaction();
        //validação dos inputs
        $validation = FormValidation::validar($this->request->all(), $this->rules);

        if ($validation !== true) {
            return $validation;
        }

        try {
            $nome = $this->request->get('nome');
            $ano = $this->request->get('ano');
            $file_path = $this->request->file('file_path')->store('public');

            $carro = Carro::find($id);

            if (!$carro) {
                return response()->json(['errors' => 'recurso não encontrado', 404]);
            }

            $carro->nome = $nome;
            $carro->ano = $ano;
            $carro->file_path = $file_path;

            $carro->save();

            DB::commit();
            return response()->json(['message' => 'Oba, deu certo!'], 200);

        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Desculpe, algo deu errado'], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $carro = Carro::find($id);
            if (!$carro) {
                return response()->json(['message' => 'Recurso não encontrado'], 401);
            }

            $carro->delete();

            DB::commit();
            return response()->json(['message' => 'recurso deletado com sucesso'], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Desculpe, algo deu errado!'], 500);
        }
    }
}
