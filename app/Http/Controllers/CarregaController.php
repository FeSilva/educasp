<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Predios;
use App\Models\Empreiteiras;

class CarregaController extends Controller
{
    public function predios(Predios $model, Request $request){
        $info = $request->all();
        $predio = $model->getCarregamento($info['codigoPredio']);

        return json_encode($predio[0]);
    }
}
