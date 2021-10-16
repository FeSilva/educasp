<?php

namespace App\Http\Controllers;

use App\Models\Pi;
use App\Models\VistoriasMultiplas;
use App\Models\Vistoria;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $naoEnviadas = 0;
        $aguardandoRetorno = 0;
        $enviados = 0;
        $pi = Pi::get();
        $vistorias = Vistoria::get();
        $vistoriaMultiplas = VistoriasMultiplas::whereIn('tipo_id', [4, 5])->where('status', 'em aprovacao')->get();

        $returnCharts = [];


        foreach ($vistorias as $vistoria) {
            switch ($vistoria->status) {
                case 'cadastro':
                    $naoEnviadas++;
                    break;
                case 'em aprovação':
                    $aguardandoRetorno++;
                    break;
                case 'Enviado':
                    $enviados++;
            }
        }


        $return = [
            'naoEnviados_LO' => $naoEnviadas,
            'emAprovacao_LO' => $aguardandoRetorno,
            'enviado_LO' => $enviados,
            'orcamentoNaoAprovado' => count($vistoriaMultiplas),
            'pis' => count($pi)
        ];

        return view('dashboard.index', compact('return'));
    }

    function returnChartJson()
    {
        //Aprovadas
        for ($mes = 1; $mes <= 12; $mes++) {
            $chartVistorias = DB::select(' 
                          SELECT count(dt_vistoria) AS count, 
                          MONTH(dt_vistoria) AS mes 
                          FROM vistorias 
                          WHERE status = "Enviado" 
                          AND MONTH(dt_vistoria) = "' . $mes . '"');
            if ($chartVistorias[0]->count > 1)
                $returnCharts[$mes] = [
                    'count' => $chartVistorias[0]->count,
                    'mes' => $chartVistorias[0]->mes
                ];
        }

        //Em aprovação
        for ($mes = 1; $mes <= 12; $mes++) {
            $chartVistoriasEmAprovacao = DB::select(' 
                          SELECT count(dt_vistoria) AS count, 
                          MONTH(dt_vistoria) AS mes 
                          FROM vistorias 
                          WHERE status <> "Enviado" 
                          AND MONTH(dt_vistoria) = "' . $mes . '"');

            $returnChartsEmaprovacao[$mes] = [
                'count' => $chartVistoriasEmAprovacao[0]->count,
                'mes' => $chartVistoriasEmAprovacao[0]->mes ?? 0
            ];
        }

        return [
            'aprovadas' => $returnCharts,
            'naoenviadas' => $returnChartsEmaprovacao
        ];
    }

    function returnChartsJsonMulti()
    {
        $status = ['Enviado', 'em aprovacao', 'cadastro', 'aprovado'];

        foreach ($status as $state) {
            for ($mes = 1; $mes <= 12; $mes++) {
                $chartVistoriasMult = DB::select(' 
                              SELECT count(dt_vistoria) AS count, 
                              MONTH(dt_vistoria) AS mes,
                              status
                              FROM vistorias_multiplas 
                              WHERE
                              status = "' . $state . '"
                              and
                              MONTH(dt_vistoria) = "' . $mes . '"');
                if ($chartVistoriasMult[0]->count > 1)

                    $returnCharts[$chartVistoriasMult[0]->status][$mes] = [
                        'count' => $chartVistoriasMult[0]->count,
                        'mes' => $chartVistoriasMult[0]->mes
                    ];
            }
        }

        return json_encode($returnCharts);
    }

    function returnChartsJsonMultType()
    {
        $status = ['Enviado', 'em aprovacao', 'cadastro', 'aprovado'];
        $tipo_id = ['4', '5', '6', '7', '8'];

        foreach ($tipo_id as $tipo) {
            foreach ($status as $state) {
                for ($mes = 1; $mes <= 12; $mes++) {
                    $chartVistoriasMult = DB::select(' 
                              SELECT count(dt_vistoria) AS count, 
                              MONTH(dt_vistoria) AS mes,
                              tipo_id as tipo
                              FROM vistorias_multiplas 
                              WHERE
                              status = "Enviado"
                              and
                              tipo_id  = "' . $tipo . '"
                              and
                              MONTH(dt_vistoria) = "' . $mes . '"');
                    if ($chartVistoriasMult[0]->count > 1)

                        $returnCharts[$chartVistoriasMult[0]->tipo][$mes] = [
                            'count' => $chartVistoriasMult[0]->count,
                            'tipo_id' => $chartVistoriasMult[0]->tipo,
                            'mes' => $chartVistoriasMult[0]->mes
                        ];
                }
            }
        }

        return json_encode($returnCharts);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
