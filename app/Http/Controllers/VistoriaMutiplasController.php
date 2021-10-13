<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VistoriasMultiplas;
use App\Models\Pi;
use App\Models\Predios;
use App\Models\VistoriaTipo;
use App\Models\Users;
use App\Models\Programas;
use Illuminate\Support\Facades\Storage;

use DB;
class VistoriaMutiplasController extends Controller
{
    //
    public function index()
    {
        $vistorias = VistoriasMultiplas::with('Predio')->get();

        return view('vistorias.multiplas.list', compact('vistorias'));
    }

    public function store(Request $request, Programas $programas)
    {
        $ids = ['4', '5', '6', '7', '8'];

        $data = $request->all();
        $fiscais = Users::where('grupo', 'fiscal')->get();

        $tipos = VistoriaTipo::whereIn('vistoria_tipo_id', $ids)->get();
        $programas    = $this->carregaProgramas($programas->getProgramas());
        if(isset($data['id'])){
            $vistoria = VistoriasMultiplas::with('Pi')
                ->with('Tipos')
                ->with('Fiscal')
                ->with('UserCreate')
                ->where('id',$data['id'])
                ->get();


            $vistoriaMultipla = $vistoria[0];


            return view('vistorias.multiplas.edit', compact('tipos', 'fiscais'),compact('vistoriaMultipla'));
        }

        return view('vistorias.multiplas.store', compact('tipos', 'fiscais'),compact('programas'));
    }

    public function create(Request $request)
    {

        $data = $request->all();

        $pi = Pi::where('codigo', $data['codigo_pi'])->first();
        $predio = Predios::where('codigo', $data['codigo_predio'])->first();
        $dt_vistoria = date("d.m.y", strtotime($data['dt_vistoria']));
        date_default_timezone_set('UTC');
        //$date = date('m',$data['dt_vistoria']);

        try {

            $name_archive = '';



            switch ($data['tipo_vistoria']){
                case 4:
                    $name_archive = 'OS_'.str_replace(".","",$data['codigo_predio']).'_'.$dt_vistoria;
                    break;
                case 5:
                    $name_archive = 'OC_'.str_replace(".","",$data['codigo_predio']).'_'.$dt_vistoria;
                    break;
                case 6:
                    $name_archive = 'RI_'.str_replace(".","",$data['codigo_predio']).'_'.$dt_vistoria;
                    break;
                case 7:
                    $name_archive = 'GS_'.str_replace("/",".",$data['codigo_pi']).'_'.$dt_vistoria;
                    break;
                case 8:
                    $name_archive = 'ST_'.str_replace("/",".",$data['codigo_pi']).'_'.$dt_vistoria;
                    break;
            }


            if(isset($data['id'])){
                if ($data['tipo_vistoria'] != '4' AND $data['tipo_vistoria'] != '5' AND $data['tipo_vistoria'] != '6') {

                    //Validar regra de GS & ST 1 por mês


                    //Validar se já existe uma vistoria deste tipo dentro do mês atual
                    //Se houver não deixar salvar, retornar msg que já existe vistoria cadastrada.
                    if($data['tipo_vistoria'] == 7){


                        VistoriasMultiplas::where('id',$data['id'])->update([
                            'user_id' => Auth()->user()->id,
                            'pi_id' => $pi->id,
                            'cod_pi' => $data['codigo_pi'],
                            'tipo_id' => $data['tipo_vistoria'],
                            'predio_id' => $predio->id,
                            'fiscal_user_id' => $data['cod_fiscal_user_id'],
                            'cod_predio'    => $data['codigo_predio'],
                            'dt_vistoria'   => $data['dt_vistoria'],
                            'check_avanco' => $data['check_avanco'],
                            'status' => 'cadastro',
                             'name_archive' => $name_archive,
                        ]);
                    }else{
                        VistoriasMultiplas::where('id',$data['id'])->update([
                            'user_id' => Auth()->user()->id,
                            'pi_id' => $pi->id,
                            'cod_pi' => $data['codigo_pi'],
                            'tipo_id' => $data['tipo_vistoria'],
                            'predio_id' => $predio->id,
                            'fiscal_user_id' => $data['cod_fiscal_user_id'],
                            'cod_predio'    => $data['codigo_predio'],
                            'dt_vistoria'   => $data['dt_vistoria'],
                            'status' => 'cadastro',
                            'name_archive' => $name_archive,
                        ]);
                    }
                    session()->flash('success', 'Vistoria Atualizada com Sucesso !');
                } ELSE {

                    //Regra para criar este tipo de vistoria ?
                    if (!$pi) {
                        $id_pi = '';
                    }else{
                        $id_pi = $pi->id;
                    }

                    if (!$predio) {
                        $id_predio = '';
                        $cod_predio = '';
                    }else{
                        $id_predio =  $predio->id;
                        $cod_predio = $predio->codigo;
                    }


                    if ($data['tipo_vistoria'] != '6') {

                        VistoriasMultiplas::where('id',$data['id'])->update([
                            'user_id'       => Auth()->user()->id,
                            'pi_id'         => $id_pi,
                            'cod_pi'        => $data['codigo_pi'],
                            'tipo_id'       => $data['tipo_vistoria'],
                            'predio_id'     => $id_predio,
                            'fiscal_user_id'=> $data['cod_fiscal_user_id'],
                            'cod_predio'    => $cod_predio,
                            'name_archive'  => $name_archive,
                            'programa'      => $data['programa'],
                            'dt_vistoria'   => $data['dt_vistoria'],
                            'num_orcamento' => $data['orcamento'],
                            'status'        => 'cadastro'
                        ]);

                    }else{
                        VistoriasMultiplas::where('id',$data['id'])->update([
                            'user_id'       => Auth()->user()->id,
                            'pi_id'         => $id_pi,
                            'cod_pi'        => $data['codigo_pi'],
                            'tipo_id'       => $data['tipo_vistoria'],
                            'predio_id'     => $id_predio,
                            'fiscal_user_id'=> $data['cod_fiscal_user_id'],
                            'cod_predio'    => $cod_predio,
                            'name_archive'  => $name_archive,
                            'dt_vistoria'   => $data['dt_vistoria'],
                            'num_orcamento' => $data['orcamento'],
                            'status'        => 'cadastro'
                        ]);
                    }

                    session()->flash('success', 'Vistoria Atualizada com Sucesso !');
                }

            }else{

                if ($data['tipo_vistoria'] != '4' AND $data['tipo_vistoria'] != '5' AND $data['tipo_vistoria'] != '6') {
                    $exist = DB::SELECT("select * from `vistorias_multiplas` where MONTH(dt_vistoria) = MONTH($data[dt_vistoria]) AND tipo_id = $data[tipo_vistoria]");
                    //Validar se já existe uma vistoria deste tipo dentro do mês atual
                    //Se houver não deixar salvar, retornar msg que já existe vistoria cadastrada.


                    if(empty($exist)){

                        if (!$pi) {
                            $id_pi = '';
                        }else{
                            $id_pi = $pi->id;
                        }

                        if (!$predio) {
                            $id_predio = '';
                            $cod_predio = '';
                        }else{
                            $id_predio =  $predio->id;
                            $cod_predio = $predio->codigo;

                        }

                        if($data['tipo_vistoria'] == 7){


                            VistoriasMultiplas::create([
                                'user_id' => Auth()->user()->id,
                                'pi_id' => $id_pi,
                                'cod_pi' => $data['codigo_pi'],
                                'tipo_id' => $data['tipo_vistoria'],
                                'predio_id' => $id_predio,
                                'name_archive' => $name_archive,
                                'programa' => $data['programa'],
                                'check_avanco' => $data['check_avanco'],
                                'fiscal_user_id' => $data['cod_fiscal_user_id'],
                                'cod_predio' => $data['codigo_predio'],
                                'dt_vistoria' => $data['dt_vistoria'],
                                'status' => 'cadastro'
                            ]);
                        }else{

                            if($request->file('file')){
                                $file = $request->file('file');
                                $filename = $file->getClientOriginalName();
                                $pastaName = 'seguranca_trabalho';
                                Storage::putFileAs('public/archives/'.$pastaName, $file, $name_archive.'.pdf');
                                //Storage::disk('public')->put("archives/{$pastaName}/{$name_archive}.pdf", $filename);
                                $filePath = "archives/{$pastaName}/{$name_archive}.pdf"; //VALIDAR NOME

                                VistoriasMultiplas::create([
                                    'user_id' => Auth()->user()->id,
                                    'pi_id' => $id_pi,
                                    'cod_pi' => $data['codigo_pi'],
                                    'tipo_id' => $data['tipo_vistoria'],
                                    'predio_id' => $id_predio,
                                    'arquivo' => $filePath,
                                    'name_archive' => $name_archive,
                                    'programa' => $data['programa'],
                                    'fiscal_user_id' => $data['cod_fiscal_user_id'],
                                    'cod_predio' => $data['codigo_predio'],
                                    'dt_vistoria' => $data['dt_vistoria'],
                                    'status' => 'cadastro'
                                ]);
                            }else{
                                session()->flash('error', 'Por favor, inclua um anexo para Segurança do Trabalho !');
                                return  redirect()->back();
                            }
                        }
                        session()->flash('success', 'Vistoria Cadastrada com Sucesso !');
                    }else{

                        $tipo = VistoriaTipo::where("vistoria_tipo_id",$data['tipo_vistoria'])->first();
                        return  redirect()->back()->with('error', 'Já existe uma vistoria de '.$tipo->name.' cadastrada este mês !');
                    }

                } ELSE {

                    //Regra para criar este tipo de vistoria ?

                    //Regra para criar este tipo de vistoria ?
                    if (!$pi) {
                        $id_pi = '';
                    }else{
                        $id_pi = $pi->id;
                    }

                    if (!$predio) {
                        $id_predio = '';
                        $cod_predio = '';
                    }else{
                        $id_predio =  $predio->id;
                        $cod_predio = $predio->codigo;
                    }

                    if ($data['tipo_vistoria'] != '6') {

                        VistoriasMultiplas::create([
                            'user_id'       => Auth()->user()->id,
                            'pi_id'         => $id_pi,
                            'cod_pi'        => $data['codigo_pi'],
                            'tipo_id'       => $data['tipo_vistoria'],
                            'predio_id'     => $id_predio,
                            'fiscal_user_id'=> $data['cod_fiscal_user_id'],
                            'cod_predio'    => $cod_predio,
                            'name_archive'  => $name_archive,
                            'programa'      => $data['programa'],
                            'dt_vistoria'   => $data['dt_vistoria'],
                            'num_orcamento' => $data['orcamento'],
                            'status'        => 'cadastro'
                        ]);

                    }else{
                        VistoriasMultiplas::create([
                            'user_id'       => Auth()->user()->id,
                            'pi_id'         => $id_pi,
                            'cod_pi'        => $data['codigo_pi'],
                            'tipo_id'       => $data['tipo_vistoria'],
                            'predio_id'     => $id_predio,
                            'fiscal_user_id'=> $data['cod_fiscal_user_id'],
                            'cod_predio'    => $cod_predio,
                            'name_archive'  => $name_archive,
                            'dt_vistoria'   => $data['dt_vistoria'],
                            'num_orcamento' => $data['orcamento'],
                            'status'        => 'cadastro'
                        ]);
                    }

                    session()->flash('success', 'Vistoria Cadastrada com Sucesso !');
                }

            }
        } catch (Exception $exception) {
            throw $exception->getMessage();
        }

        return redirect()->route('multiplas.list');
    }


    public function excluir(Request $request){
        $data = $request->all();

        $vistoria = VistoriasMultiplas::where('id',$data['id'])->first();


        if($vistoria->status == 'cadastro'){
            VistoriasMultiplas::where('id',$data['id'])->delete();
            session()->flash('success', 'Vistoria excluida com sucesso!');
            return redirect()->route('multiplas.list');
        }else if($vistoria['status'] == 'Aprovado'){

            session()->flash('success', 'Vistoria já foi aprovada e não pode ser excluída !');
            return redirect()->route('multiplas.list');
        }else{
            session()->flash('success', 'Vistoria já foi enviada e não pode ser excluída !');
            return redirect()->route('multiplas.list');
        }
    }

    public function carregaProgramas($programas)
    {

        if ($programas) {
            foreach ($programas as $programa) {
                $return[$programa['id']] = $programa['name'];
            }
        }else{
            return "Nenhum usuário cadastrado como fiscal.";
        }

        return $return;
    }
}
