<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Predios;
use App\Models\Pi;
use App\Models\Vistoria;
use App\Models\VistoriasMultiplas;
use App\Models\VistoriaTipo;
use App\Models\VistoriaItemAndamento;
use App\Models\Empreiteiras;
use function Illuminate\Events\queueable;

class CarregaController extends Controller
{
    public function predios(Predios $model, Request $request){
        $info = $request->all();
        $predio = $model->getCarregamento($info['codigoPredio']);

        return json_encode($predio[0]);
    }

    public function vistorias(Vistoria $model,Request $request){
        $info = $request->all();

        return $model->where('status','cadastro')->with('pi')->with('tipos')->with('pi.predios')->get();
    }

    public function vistoriasMultiplas(VistoriasMultiplas $model,Request $request){
        $info = $request->all();

        $return = $model->where('status','cadastro')->where('tipo_id',$info['tipoVistoria'])->with('Pi')->with('Tipos')->with('Predio')->get();

        return $return;
    }


    public function processoIntervencao(Pi $model, Request $request){
        $info = $request->all();
        $processoIntervencao = $model->with('predios')
                ->with('items')->with('User')
                ->with('Empreiteiras')
                ->with('items.AndamentoItems')
                ->with(array('vistorias' => function($query) {
                    $query->orderBy('dt_vistoria', 'DESC');
                }))
                ->with('vistorias.tipos')
                ->where('codigo',$info['codigoPi'])
                ->get()->sortByDesc('vistorias.dt_vistoria');

        $vistorias = Vistoria::where('pi_id', $processoIntervencao[0]->id)->get();

        $vistoriaTipos = VistoriaTipo::where('status',1)->get();

        $tipos = [];

        if(isset($vistorias[0])){
            if ($this->checkVistoriaIsOneHundredPorcent($vistorias)) {
                return json_encode(['error' => 'Esse código já concluiu o processo de avanço']);
            }
            foreach($vistorias as $vistoria){
                 switch($vistoria){
                     case $vistoria->tipo_id == '1':
                         $tipos = [
                             '3' => 'Fiscalização'
                         ];
                         break;
                     case $vistoria->tipo_id == '2':
                         $tipos = [
                             '1' => 'Abertura',
                             '3' => 'Fiscalização'
                         ];
                         break;
                     case $vistoria->tipo_id == '3':
                         $aberturaConsult = Vistoria::where('tipo_id',1)
                             ->where('pi_id',$processoIntervencao[0]->id)
                             ->get();


                         if(count($aberturaConsult) > 0 ){
                            //$aberturaConsult->where(DB::RAW('dt_vistoria BETWEEN dt_vistoria AND (dt_vistoria + INTERVAL 5 DAY) '))
                             $tipos = [
                                 '3' => 'Fiscalização'
                             ];
                         }else{
                            $tipos = [
                                '1' => 'Abertura',
                                '3' => 'Fiscalização'
                            ];
                         }
                         break;
                 }

            }
        }else{
            foreach($vistoriaTipos as $key => $tiposArray){
                if($tiposArray->vistoria_tipo_id != '3'){
                    $tipos[$tiposArray->vistoria_tipo_id] = $tiposArray->name;
                }
            }
        }

        $processoIntervencao[0]->tipos = $tipos;
        return json_encode($processoIntervencao[0]);
    }

    private function checkVistoriaIsOneHundredPorcent($vistorias)
    {
        foreach($vistorias as $vistoria) {
            if ((int) $vistoria->avanco_fisico == 100) {
                return true;
            }
        }

        return false;
    }
}
