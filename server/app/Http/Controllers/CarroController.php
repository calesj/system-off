<?php

namespace App\Http\Controllers;

use App\Models\Carro;

class CarroController extends ControllerBase
{
   public function __construct()
   {
       $this->model = Carro::class;
   }
}
