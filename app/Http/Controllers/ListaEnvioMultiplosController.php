<?php

namespace App\Http\Controllers;

use App\Mail\ListaEnvioMail;
use App\Models\ListaEnvio;
use App\Models\EnvioEmail;
use App\Models\ListaEnviosMultiplos;
use App\Models\ListaVistoriaMultiplos;
use App\Models\User;
use App\Models\Vistoria;
use App\Models\ListaVistoria;
use App\Models\VistoriasMultiplas;
use App\Models\VistoriaTipo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ListaEnvioMultiplosController extends Controller
{
    public function index()
    {
        $enviados = DB::select(
            "SELECT list.codigo_lista as codigo_lista,
            list.id as id,
            list.mes as mes,
            tipos.name as tipo,
            vistoria.status as status,
            enviado_por.name as enviado_por,
            COUNT(vistoria.id) AS qtd_vistoria
            FROM lista_envios_multiplos AS list
            INNER JOIN lista_vistoria_envios_multiplos AS envios ON list.id = envios.lista_id
            INNER JOIN vistorias_multiplas AS vistoria ON vistoria.id = envios.vistoria_id
            INNER JOIN vistoria_tipos as tipos ON tipos.vistoria_tipo_id = vistoria.tipo_id
            LEFT JOIN users AS enviado_por ON enviado_por.id = list.user_id
            WHERE vistoria.status = 'enviado'
            GROUP BY list.codigo_lista, mes, id ORDER BY mes ASC");

        $aprovadas = VistoriasMultiplas::where('status', 'Aprovado')->get();
        $qtdAprovadas = count($aprovadas);

        return view('vistorias.listaenvios.multiplas.list', compact('enviados', 'qtdAprovadas'));
    }

    public function create()
    {
        $aprovadas = VistoriasMultiplas::where('status', 'Aprovado')->get();
        $tipos = VistoriaTipo::whereIn('vistoria_tipo_id',['4','5','6','7','8'])->get();

        $qtdAprovadas = count($aprovadas);

        return view('vistorias.listaenvios.multiplas.create', compact('qtdAprovadas','aprovadas'),compact('tipos'));
    }

    public function store($id)
    {
        $listaEnvio  = DB::select(
            "SELECT 
                    list.codigo_lista as codigo_lista,
                    vistoria.id as id,
                    list.id as list_id,
                    list.mes as mes,
                    vistoria.status as status,
                    vistoria.dt_vistoria as dt_vistoria,
                    users.name as usuario_nome,
                    users.grupo as usuario_grupo,
                    fiscal.name as fiscal,
                    vistoria.arquivo as arquivo,
                    tipos.name as tipos_vistoria
                    FROM vistorias_multiplas AS vistoria
                    LEFT JOIN vistoria_tipos as tipos ON tipos.vistoria_tipo_id = vistoria.tipo_id
                    LEFT JOIN lista_vistoria_envios_multiplos AS envios ON vistoria.id = envios.vistoria_id
                    LEFT JOIN lista_envios_multiplos AS list ON list.id = envios.lista_id
                    LEFT JOIN users ON users.id = list.user_id 
                    LEFT JOIN users as fiscal ON fiscal.id = vistoria.fiscal_user_id
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
                'dt_vistoria'   => $lista->dt_vistoria,
                'status'        => $lista->status,
                'fiscal'        => $lista->fiscal,
                'tipos_vistoria' => $lista->tipos_vistoria,
                'arquivo'       => $lista->arquivo
            ];
        }

        $aListEnvio['vistorias'] = $aVistorias;
        return view('vistorias.listaenvios.multiplas.store', compact('id', 'aListEnvio'));
    }

    public function consultaMes(Request $request){
        $data = $request->all();
        $mes = intval($data['mes']);
        $countSeq = ListaEnviosMultiplos::Select(DB::RAW('count(id) +1 as seq'))->where('mes',$mes)->where('tipo_id',$data['tipoVistoria'])->get();
        $seq = null;
        switch ($countSeq[0]->seq)
        {
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

        if(isset($data['idList']))
        {
            $listaVistorias_id = ListaVistoriaMultiplos::select('vistoria_id')->where('lista_id', $data['idList'])->get();
            $aprovados = VistoriasMultiplas::whereIn('id', $listaVistorias_id)->with("Tipos")->where('status', 'Enviado')->get();
        } else {
            $aprovados = VistoriasMultiplas::where('status', 'Aprovado')->where('tipo_id', $data['tipo_vistoria'])->with("Tipos")->get();
        }

        $vistorias = [];
        $totalSize = 0;
        $totalBytesSize = 0;
        $maxSize = 10485760; //10MB
        $countVistoria = 0;
        foreach ($aprovados as $aprovado) {
            $archiveNotStorage = str_replace("storage/", "", $aprovado->arquivo);
            $size = Storage::disk('public')->size($archiveNotStorage);
            $totalSize += $size;
            if ($totalSize <= $maxSize) {
                $countVistoria++;
                $totalBytesSize += $size;
                $vistorias[] = [
                    'id' => $aprovado->id,
                    'size' => $size,
                    'tipos_name' => $aprovado->Tipos->name,
                    'archive' => $aprovado->arquivo,
                    'codigo' => $aprovado->codigo,
                    'data' => $aprovado->dt_vistoria,
                    'status' => $aprovado->status,

                ];
            }

        }

        $vistorias['totalSizeBytes'] = $totalBytesSize;
        $vistorias['qtyVistoria'] = $countVistoria;
        $vistorias['totalSizeMb'] = $this->convert_bytes_to_specified($vistorias['totalSizeBytes']);

        return response()->json($vistorias);
    }

    public function convert_bytes_to_specified($bytes, $decimal_places = 1)
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

    public function viewEmail(){
        return view('emails.listaenvio');
    }

    public function enviarEmail(Request $request)
    {
        $data = $request->all();
        $vistoriasEnviadas = isset($data['vistorias_enviadas']) ? true : false;

        try {

            DB::beginTransaction();
            //Verifica se já existe uma lista de envio com este código/mês.
            $listaConsult = ListaEnviosMultiplos::where('codigo_lista',$data['codigo'])->where('mes',$data['mes'])->where('tipo_id',$data['tipoVistoria'])->first();

            if(isset($listaConsult)){
                session()->flash('error', 'A lista de envio '.$data['codigo'].' do mês de '.$data['mes'].'já foi enviada para a FDE, por favor olhe na lista de envio.');
                return redirect()->back();
            }


            $lista = ListaEnviosMultiplos::create(
                [
                    'codigo_lista' => $data['codigo'],
                    'mes' => $data['mes'],
                    'user_id' => Auth()->user()->id,
                    'tipo_id' => $data['tipoVistoria'],
                ]);


            foreach ($data['vistoria_ids'] as $id) {

                $vistoria = VistoriasMultiplas::where('id', $id)->first();
                ListaVistoriaMultiplos::create([
                    'lista_id' => $lista->id,
                    'vistoria_id' => $id,
                    'created_at' => now()
                ]);


                $vistoria->status = 'Enviado';
                $vistoria->save();
            }

            /*EnvioEmail::create([
                'user_id'   => Auth()->user()->id,
                'lista_id'  => $lista->id,
                'mes'       => $data['mes']
            ]);*/

            Mail::send(new ListaEnvioMail($data['vistoria_ids'],$lista->id,$data['codigo'], $data['mes'],'MULTIPLAS', $vistoriasEnviadas));


            DB::commit();
            session()->flash('success', 'Email enviado com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }
}
