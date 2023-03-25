<?php

namespace App\Form;
use Illuminate\Support\Facades\Validator;

Class FormValidation {
    static function validar(array $data,array $rules)
    {
        $message = [
          'required' => 'O campo :attribute é obrigatório',
            'image' => 'Coloque uma imagem válida',
        ];
        $validator = Validator::make($data, $rules, $message);

        if($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return true;
    }
}
