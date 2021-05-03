<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Predios;
use App\Models\Pi;
use App\Models\Empreiteiras;
use function Illuminate\Events\queueable;

class CarregaController extends Controller
{
    public function predios(Predios $model, Request $request){
        $info = $request->all();
        $predio = $model->getCarregamento($info['codigoPredio']);

        return json_encode($predio[0]);
    }

    public function processoIntervencao(Pi $model, Request $request){
        $info = $request->all();
        $processoIntervacao = $model->with('predios')->where('codigo',$info['codigoPi'])->get();

        return json_encode($processoIntervacao[0]);
    }
}
