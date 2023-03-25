<?php

namespace App\Http\Controllers;
use App\Models\Moto;

class MotoController extends ControllerBase
{
    public function __construct()
    {
        $this->model = Moto::class;
    }
}
