<?php

namespace App\Http\Controllers;

use App\Models\Pi;
use App\Models\VistoriasMultiplas;
use App\Models\Vistoria;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

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
        $naoEnviadas = Vistoria::whereNotIn('status', ['Enviado','Em aprovação'])->get(); //NOT IN
        $naoEnviadasMultipplas = VistoriasMultiplas::whereNotIn('status', ['Enviado','Em aprovação'])->get();//NOT IN
        $vistoriaMultiplas = VistoriasMultiplas::whereIn('tipo_id', [4, 5])->where('status', 'em aprovacao')->get();

        $vistoriasMult = VistoriasMultiplas::whereIN('status', ['cadastro','Aprovado'])->get();
        $vistorias = Vistoria::whereIN('status', ['cadastro','Aprovado'])->get();

        $beforeSendCount = count($vistoriasMult) + count($vistorias);
        $returnCharts = [];

        $notSednCount = count($naoEnviadas) + count($naoEnviadasMultipplas);
        $calendarPis = $this->viewCalendarPis();
        $return = [
            'naoEnviados_LO' => $notSednCount,
            'emAprovacao_LO' => $beforeSendCount,
            'enviado_LO' => $enviados,
            'orcamentoNaoAprovado' => count($vistoriaMultiplas),
            'calendarPis' => $calendarPis
        ];

        return view('dashboard.index', compact('return'));
    }

    function returnChartJson()
    {
        $returnChartsEmaprovacao = [];
        $returnCharts = [];
        //Aprovadas
        for ($mes = 1; $mes <= 12; $mes++) {
            $chartVistorias = DB::select(' 
                          SELECT count(dt_vistoria) AS count, 
                          MONTH(dt_vistoria) AS mes 
                          FROM vistorias 
                          WHERE status = "Enviado" 
                          AND MONTH(dt_vistoria) = "' . $mes . '"');
            if ($chartVistorias[0]->count > 0)
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
        $status = ['Enviado', 'em aprovacao', 'cadastro', 'Aprovado'];

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
                if ($chartVistoriasMult[0]->count > 0) {
                    $returnCharts[$chartVistoriasMult[0]->status][$mes] = [
                        'count' => $chartVistoriasMult[0]->count,
                        'mes' => $chartVistoriasMult[0]->mes
                    ];
                }
            }
        }

        return json_encode($returnCharts);
    }

    function returnChartsJsonMultType()
    {
        $returnChartsEmaprovacao = [];
        $returnCharts = [];
        //Aprovadas
        for ($mes = 1; $mes <= 12; $mes++) {
            $chartVistorias = DB::select(' 
                    SELECT count(*) AS count, 
                    MONTH(dt_vistoria) AS mes,
                    tipo_id as tipo  
                    FROM vistorias_multiplas 
                    WHERE status = "Enviado" 
                    AND MONTH(dt_vistoria) = "' . $mes . '"');
            if ($chartVistorias[0]->count > 0) {
                $returnCharts[$mes] = [
                    'count' => $chartVistorias[0]->count,
                    'type' => $chartVistorias[0]->tipo,
                    'mes' => $mes
                ];
            }
        }

        //Em aprovação

        for ($mes = 1; $mes <= 12; $mes++) {
            $chartVistoriasEmAprovacao = DB::select(' 
                          SELECT count(*) AS count, 
                          MONTH(dt_vistoria) AS mes,
                          tipo_id as tipo 
                          FROM vistorias_multiplas 
                          WHERE status <> "Enviado" 
                          AND MONTH(dt_vistoria) = "' . $mes . '"');

            if ($chartVistoriasEmAprovacao[0]->count > 0) {
                $returnChartsEmaprovacao[$mes] = [
                    'count' => $chartVistoriasEmAprovacao[0]->count,
                    'type' => $chartVistoriasEmAprovacao[0]->tipo,
                    'mes' => $mes
                ];
            }
        }

        return [
            'aprovadas' => $returnCharts,
            'naoenviadas' => $returnChartsEmaprovacao
        ];
    }

    function sintenticTableVistorias()
    {

        $vistorias = DB::SELECT("
		
		SELECT 
		#LO
		CASE
			WHEN vistorias.tipo_id = 1 THEN 'Abertura'
			WHEN vistorias.tipo_id = 2 THEN 'Transferência'
			WHEN vistorias.tipo_id = 3 THEN 'Fiscalização'
		END AS tipo,
		COUNT(*) as total,
		SUM(CASE WHEN status IN ('cadastro','aprovado') then 1 else 0 end) AS tipo_mult_total_naoenviada, 
		SUM(CASE WHEN status in ('em aprovação') then 1 else 0 end) AS total_mult_total_naoretornadas

		FROM vistorias
		GROUP BY tipo_id
		
		UNION 
		
		SELECT 
		#Multiplas
		CASE 
			WHEN tipo_id = 4 THEN 'Orçamento Simples'
			WHEN tipo_id = 5 THEN 'Orçamento Complexo'
			WHEN tipo_id = 6 THEN 'Específica'
			WHEN tipo_id = 7 THEN 'Gestão Social'
			WHEN tipo_id = 8 THEN 'Segurança do Trabalho'
		END AS tipo,
		COUNT(*) as total,
		SUM(CASE WHEN status IN ('cadastro','aprovado') then 1 else 0 end) AS tipo_mult_total_naoenviada, 
		SUM(CASE WHEN status in ('em aprovação') then 1 else 0 end) AS total_mult_total_naoretornadas
		FROM vistorias_multiplas
		GROUP BY tipo_id
		");

        foreach ($vistorias as $vistoria) {

                $vistoriasLO[$vistoria->tipo] = [
                    'tipo' => $vistoria->tipo,
                    'total' => $vistoria->tipo_total,
                    'nao_enviadas' => $vistoria->tipo_total_naoenviada ?? 0,
                    'nao_retornadas' => $vistoria->total_total_naoretornadas ?? 0
                ];

                $vistoriasMult[$vistoria->tipo_mult] = [
                    'tipo' => $vistoria->tipo_mult,
                    'total' => $vistoria->tipo_mult_total,
                    'nao_enviadas' => $vistoria->tipo_mult_total_naoenviada ?? 0,
                    'nao_retornadas' => $vistoria->tipo_mult_total_naoretornadas ?? 0,
                ];
        }

        $aMerge = array_merge($vistoriasLO,$vistoriasMult);

        return Datatables::of($aMerge)
            ->make(true);
    }

    private function viewCalendarPis()
    {
        $itens = [];
        $calendarItens = DB::table('calendario_pi')->get();
        foreach ($calendarItens as $key => $item) {
            if ($item->diferenca_emDias < 0) {
                $calendarItens[$key]->qtde_previsao = null;
            } else {
                //Transformar qtde de dias em meses
                $meses = $item->diferenca_emDias / 30; // USANDO 22 DIAS como referencia para 1 mes
                $qtde_vistorias_mais = (int) round($meses * 4);

                $calendarItens[$key]->qtde_previsao = $qtde_vistorias_mais;
            }
            $calendarItens[$key]->total = $item->qtd_atual + $calendarItens[$key]->qtde_previsao;
            $calendarItens[$key]->saldo = $calendarItens[$key]->total - $item->total_enviadas;

            $itens[] = $calendarItens[$key];
        }
        return $itens;
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
