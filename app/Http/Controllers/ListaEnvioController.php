<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Vistoria;
use App\Models\EnvioEmail;
use App\Models\ListaEnvio;
use App\Mail\ListaEnvioMail;
use Illuminate\Http\Request;
use App\Models\ListaVistoria;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;



class ListaEnvioController extends Controller
{
    protected $service;
    
    public function __construct()
    {
        //$this->service = $service;
    }

    public function index()
    {
        //$lista = $this->service->index(); //Irá conter toda lógica da listagem
        
        $enviados = DB::select(
            "SELECT
                    list.codigo_lista as codigo_lista,
                    list.id as id,
                    list.mes as mes,
                    vistoria.status as status,
                    enviado.name as enviado_por,
                    COUNT(vistoria.id) AS qtd_vistoria
                    FROM lista_envios AS list
                    INNER JOIN lista_vistoria_envios AS envios ON list.id = envios.lista_id
                    INNER JOIN vistorias AS vistoria ON vistoria.id = envios.vistoria_id
                    LEFT JOIN users AS enviado ON enviado.id = list.user_id
                    GROUP BY list.codigo_lista, mes ORDER BY mes asc
            ");

        $aprovadas = Vistoria::where('status', 'aprovado')->get();
        $qtdAprovadas = count($aprovadas);
        return view('vistorias.listaenvios.list', compact('enviados', 'qtdAprovadas'));
    }

    public function create()
    {
        $aprovadas = Vistoria::where('status', 'aprovado')->get();
        $qtdAprovadas = count($aprovadas);

        /*$countSeq = ListaEnvio::Select(DB::RAW('count(id) +1 as seq'))->where(->get();
        $seq = null;

        switch ($countSeq[0]->seq){
            case $countSeq[0]->seq < 10:
                $seq = '000'.$countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 9:
                $seq = '00'.$countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 100:
                $seq = '0'.$countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 999:
                $seq = $countSeq[0]->seq;
                break;
        }*/

        return view('vistorias.listaenvios.create', compact('qtdAprovadas'));
    }

    public function store($id){
        $listaEnvio  = DB::select(
            "SELECT 
                    vistoria.codigo as codigo_vistoria,
                    list.codigo_lista as codigo_lista,
                    vistoria.id as id,
                    list.id as list_id,
                    list.mes as mes,
                    vistoria.status as status,
                    vistoria.dt_vistoria as dt_vistoria,
                    users.name as usuario_nome,
                    users.grupo as usuario_grupo,
                    vistoria.arquivo as arquivo
                    FROM vistorias AS vistoria
                    LEFT JOIN lista_vistoria_envios AS envios ON vistoria.id = envios.vistoria_id
                    LEFT JOIN lista_envios AS list ON list.id = envios.lista_id
                    LEFT JOIN users ON users.id = list.user_id 
                    WHERE list.id = ".$id."        
            ");


        $aListEnvio =[];
        $aVistorias =[];


        foreach($listaEnvio as $key => $lista){
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            $date = date("F", mktime(0, 0, 0, $lista->mes, 10));
            $mes = ucfirst( utf8_encode( strftime("%B", strtotime($date))));
            $aListEnvio = [
                'codigo_lista' => $lista->codigo_lista,
                'mes'          => $mes,
                'usuario'      => $lista->usuario_nome,
                'grupo'        => $lista->usuario_grupo
            ];

            $aVistorias[$key] = [
                'codigo'        => $lista->codigo_vistoria,
                'dt_vistoria'   => $lista->dt_vistoria,
                'status'        => $lista->status,
                'arquivo'       => $lista->arquivo
            ];
        }

        $aListEnvio['vistorias'] = $aVistorias;

        return view('vistorias.listaenvios.store', compact('id', 'aListEnvio'));
    }

    public function consultaMes(Request $request){
        $data = $request->all();
        $mes = intval($data['mes']);
        $countSeq = ListaEnvio::Select(DB::RAW('count(id) +1 as seq'))->where('mes',$mes)->get();
        $seq = null;

        switch ($countSeq[0]->seq) {
            case $countSeq[0]->seq < 10:
                $seq = '00'.$countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 9:
                $seq = '0'.$countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 100:
                $seq = $countSeq[0]->seq;
                break;
        }

        return $seq."/".date('Y');
    }
    public function carregar(Request $request)
    {
        $data = $request->all();

        if(isset($data['idList'])){
            $listaVistorias_id = ListaVistoria::select('vistoria_id')->where('lista_id',$data['idList'])->get();

            $aprovados = Vistoria::whereIn('id',$listaVistorias_id)->where('status', 'Enviado')->get();
        }else{
            $aprovados = Vistoria::where('status', 'Aprovado')->get();
        }

        $vistorias = [];
        $totalSize = 0;
        $totalBytesSize = 0;
        $maxSize = 10485760; //10MB

        foreach ($aprovados as $aprovado) {
            $archiveNotStorage = str_replace("storage/", "", $aprovado->arquivo);
            $size = Storage::disk('public')->size($archiveNotStorage);
            $totalSize += $size;
            if ($totalSize <= $maxSize) {
                $totalBytesSize += $size;
                $vistorias[] = [
                    'id' => $aprovado->id,
                    'size' => $size,
                    'archive' => $aprovado->arquivo,
                    'codigo' => $aprovado->codigo,
                    'data' => $aprovado->dt_vistoria,
                    'status' => $aprovado->status

                ];
            }

        }

        $vistorias['totalSizeBytes'] = $totalBytesSize;
        $vistorias['totalSizeMb'] = $this->convert_bytes_to_specified($vistorias['totalSizeBytes']);

        return response()->json($vistorias);
    }

    function convert_bytes_to_specified($bytes, $decimal_places = 1)
    {
        $formulas = array(
            'K' => number_format($bytes / 1024, $decimal_places),
            'M' => number_format($bytes / 1048576, $decimal_places),
        );

        if ($formulas['K'] > '0.0' and $formulas['M'] > '0.0') {
            return $formulas['M'] . " MB";
        } else {
            return $formulas['K'] . " KB";
        }
    }

    public function viewEmail()
    {
        return view('emails.listaenvio');
    }
    public function enviarEmail(Request $request)
    {
        $data = $request->all();

        try {
 
            $vistoriasEnviadas = isset($data['vistorias_enviadas']) ? true : false;
            //Verifica se já existe uma lista de envio com este código/mês.
            $listaConsult = ListaEnvio::where('codigo_lista',$data['codigo'])->where('mes',$data['mes'])->first();

            if(isset($listaConsult)){
                session()->flash('error', 'A lista de envio '.$data['codigo'].' do mês de '.$data['mes'].'já foi enviada para a FDE, por favor olhe na lista de envio.');
                return redirect()->back();
            }


            $lista = ListaEnvio::create(
            [
                'codigo_lista' => $data['codigo'],
                'mes' => $data['mes'],
                'user_id' => Auth()->user()->id
            ]);


            foreach ($data['vistoria_ids'] as $id) {

                $vistoria = Vistoria::where('id', $id)->with('user')->first();
                ListaVistoria::create([
                    'lista_id' => $lista->id,
                    'vistoria_id' => $id,
                    'created_at' => now()
                ]);


                $vistoria->status = 'Enviado';
                $vistoria->save();
            }

            EnvioEmail::create([
                'user_id'   => Auth()->user()->id,
                'lista_id'  => $lista->id,
                'mes'       => $data['mes']
            ]);

            $mail =   Mail::to("felipe.silva@cespfde.com.br")->send(new ListaEnvioMail($data['vistoria_ids'], $lista->id,$data['codigo'], $data['mes'],'SIMPLES', $vistoriasEnviadas));

            session()->flash('success', 'Email enviado com sucesso!');
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }
}
