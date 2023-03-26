<?php

namespace App\Http\Controllers;

use App\Models\Carro;

class CarroController extends ControllerBase
{
   public function __construct()
   {
       //passando a classe como parâmetro para o $this->model do ControllerBase
       $this->model = Carro::class;
   }
}
