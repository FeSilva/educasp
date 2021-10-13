<?php

namespace App\Http\Controllers;

use App\Http\Requests\CadastroVistoriaRequest;
use App\Http\Requests\VistoriaRequest;
use App\Models\Pi;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Vistoria;
use App\Models\VistoriaTipo;
use App\Models\VistoriaItemAndamento;
use App\Models\VistoriaAndamentos;
use App\Models\VistoriaRitmos;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use File;
use ZipArchive;

class VistoriaController extends Controller
{
    private $model;

    public function __construct(Vistoria $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $vistorias = Vistoria::with('tipos')->orderBy('dt_vistoria','desc')->get();
        //Agruppar query
        return view('vistorias.list', compact('vistorias'));
    }

    public function store(Request $request)
    {
        $info = $request->all();
        $vistoriaTipos = VistoriaTipo::where('status', 1)->get();
        $vistoriaAndamentos = VistoriaAndamentos::where('status', 1)->get();
        $vistoriaRitmos = VistoriaRitmos::where('status', 1)->get();

        if (isset($info['id'])) {
            $vistoriaReturn = Vistoria::with('andamentos')
                ->with('ritmos')
                ->with('tipos')
                ->with('pi.predios')
                ->with('AndamentoItems.Items')
                ->where('id', $info['id'])->get();
            $vistoria = $vistoriaReturn[0];



            if($caminho = Storage::disk('public')->exists($vistoria->arquivo)){
                $caminho = Storage::disk('public')->url($vistoria->arquivo);
            }else{
                $caminho = '';
            }


            return view('vistorias.edit')
                ->with(compact('vistoriaTipos', 'vistoriaRitmos'))
                ->with(compact('vistoriaAndamentos', 'vistoria'))
                ->with(compact('caminho'));
        }

        return view('vistorias.store')
            ->with(compact('vistoriaTipos', 'vistoriaRitmos'))
            ->with(compact('vistoriaAndamentos'));

    }

    public function excluir(Request $request){
        $data = $request->all();

        $vistoria = Vistoria::find($data['id'])->get();

        if($vistoria['status'] == 'cadastro'){

            $vistoriasAndamento = VistoriaItemAndamento::where('vistoria_id',$data['id'])->delete();

            $vistoriasAndamento = Vistoria::find($data['id'])->delete();

            session()->flash('success', 'Vistoria excluida com sucesso!');
            return redirect()->route('vistorias.list');
        }else if($vistoria['status'] == 'Aprovado'){


            session()->flash('success', 'Vistoria já foi aprovada e não pode ser excluída !');
            return redirect()->route('vistorias.list');
        }else{


            session()->flash('success', 'Vistoria já foi enviada e não pode ser excluída !');
            return redirect()->route('vistorias.list');
        }
    }

    public function download(Request $request)
    {
        $info = $request->all();
        $path = storage_path('app/public/' .$info['filename']);

        dd($path);

        if (!File::exists($path)) {
            abort(404);
        }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function create(Vistoria $model, VistoriaItemAndamento $andamentoItem, CadastroVistoriaRequest $request)
    {
        try {
            $info = $request->all();

            if (isset($info['id'])) {
                switch ($info['tipo_vistoria']) {
                    case '1': //Vistoria de Abertura
                        $vistoria = $model->updateVistoriaAbertura($info);
                        break;
                    default: //Vistoria de transferência / Fiscalização;
                        $vistoria = $model->updateVistoria($info);
                        break;
                }
                session()->flash('success', 'Vistoria atualizada com sucesso!');
            } else {

                $file = $request->file('arquivo_lo');
                $filePath = ''; //Declarar caminho arquivo.
                if ($file) { //verifica se existe um arquivo.
                    if ($info['tipo_vistoria'] == 1) {
                        $file = $request->file('arquivo_folha');
                    }
                    $filePath = $this->saveFile($file, $info);//Salvar o arquivo.
                    $vistoria = '';
                    switch ($info['tipo_vistoria']) {
                        case '1': //Vistoria de Abertura
                            $vistoria = $model->createVistoriaAbertura($info, $filePath);
                            break;
                        default: //Vistoria de transferência / Fiscalização;
                            $vistoria = $model->createVistoria($info, $filePath);
                            $andamentoItem->createAndamentoItem($vistoria, $info);
                            break;
                    }
                }else{
                    switch ($info['tipo_vistoria']) {
                        case '1': //Vistoria de Abertura
                            $vistoria = $model->createVistoriaAbertura($info, $filePath);
                            break;
                        default: //Vistoria de transferência / Fiscalização;
                            $vistoria = $model->createVistoria($info, $filePath);
                            $andamentoItem->createAndamentoItem($vistoria, $info);
                            break;
                    }
                }
                session()->flash('success', 'Vistoria cadastrada com sucesso!');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Ocorreu um erro ao cadastrar a vistoria.');
        }

        return redirect()->route('vistorias.list');
    }

    private function saveFile($file, $info)
    {
       /* $pi = Pi::where('codigo', $info['codigo_pi'])->with('User')->first();
        $date = Carbon::now();
        $dateFormat = date('d-m-y', strtotime($date));

        $date = str_replace("-", ".", $dateFormat); //dd.mm.y
        $codigoPi = str_replace("/", "_", $info['codigo_pi']);// return xxxx_xxxx

        $fileName = $codigoPi . '/lo_' . $codigoPi . '_' . $date . '_' . Auth()->user()->cod_user_fde . '.pdf';
        Storage::putFileAs('public', $file, $fileName);
        $fileName = 'lo_' . $codigoPi . '_' . $date . '_' . $pi->user[0]->cod_user_fde . '.pdf';
        return "{$fileName}";*/
    }

    public function validateDate(Request $request)
    {
        $info = $request->all();

        try {
            $dt_now = $info['dt_now'];
            $dt_last = $info['dt_last'];

            $date1 = date_create($dt_now);
            $date2 = date_create($dt_last);
            $diff = date_diff($date2, $date1);
            $diferenca = $diff->format("%R%a");

            $return = $this->validateCheck($diferenca,$dt_now);


            if($return == 1){
                return 'sucesso';
            }else if($return == 2){
                return 'Não é possivel registar uma vistoria aos finais de semana';
            }else{
                return 'Já existe uma vistoria em menos de 5 dias';
            }


        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    function validateCheck($diferenca,$dt_now)
    {

        $date =date_create($dt_now);
        $dia = $date->format('D');


        if($dia == 'Sat' || $dia == 'Saturday'){
            return 2;
        }

        if($dia == 'Sun' || $dia == 'Sunday'){
            return 2;
        }
        if($diferenca <= '+4'){
            return 0;
        }

        if($diferenca >= '+5'){
            return 1;
        }
    }

}
