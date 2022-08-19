<?php

namespace App\Http\Controllers;

use App\Http\Requests\PiRequest;
use App\Http\Requests\ProgramasRequest;
use App\Models\Empreiteiras;
use App\Models\Pi;
use App\Models\Users;
use App\Models\Programas;
use Couchbase\Exception;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PiController extends Controller
{

    public function index(request $request)
    {
        $returnPi = Pi::with('predios')->with('user')->with('empreiteiras')->get();
        $pis = $returnPi->toArray();
        if ($request->ajax()) {

            $returnPi = Pi::with('predios')->with('user')->with('empreiteiras')->get();

            return Datatables::of($returnPi)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('pi.store', ['id' => $row->id]) . '" class="btn btn-primary">Editar</a> <a onclick="deletePI(' . $row->id . ')" class="btn btn-warning">Desativar</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pi.list')->with(compact('pis'));
    }



    public function store(Pi $model, Users $users, Empreiteiras $empreiteiras, Programas $programas, Request $request)
    {
        $info = $request->all();
        $fiscais = $this->carregaFiscais($users->getSelectFiscal());
        $empreiteiras = $this->carregaEmpreiteiras($empreiteiras->getEmpreiteiras());
        $programas    = $this->carregaProgramas($programas->getProgramas());

        $oPi = [ //Criar Table/model para tipos de items ?
            "Reforma" => "Reforma",
            "AVCB - Obtenção" => "AVCB - Obtenção",
            "AVCB - Renovação" => "AVCB - Renovação",
            "Acessibilidade" => "Acessibilidade",
            "Obra Nova" => 'Obra Nova'
        ];


        $items = [ //Criar Table/model para tipos de items ?
            'Reforma' => 'Reforma',
            "Ampliação" => 'Ampliação',
            "Adequação" => 'Adequação',
            "Construção" => 'Construção',
            "Obra Nova" => 'Obra Nova',
            "Demolição" => 'Demolição',
            "Paisagismo" => 'Paisagismo'
        ];

        $contratacao = [
            'ATA' => 'ATA',
            'LIC' => 'LIC',
            'D.L.' => 'D.L.',
            'C.V' => 'Carta Convite',
            'LIC' => 'Licitação',
            'DISPLIC' => 'Dispensa Licitação',
        ];

        if (isset($info['id'])) {
            $pi = $model
                ->with('predios')
                ->with('user')
                ->with('empreiteiras')
                ->with('Items')
                ->where('id', $info['id'])->get()->toArray();

            $pi = [
                'id' => $pi[0]['id'],
                'codigo' => $pi[0]['codigo'],
                'dt_assinatura' => $pi[0]['dt_assinatura'],
                'id_predio' => $pi[0]['id_predio'],
                'id_empreiteira' => $pi[0]['id_empreiteira'],
                'id_user' => $pi[0]['id_user'],
                'contratacao' => $pi[0]['tipo_contratacao'],
                'valor_total' => $pi[0]['valor_total'],
                'prazo_total' => $pi[0]['prazo_total'],
                'programa'    => $pi[0]['programa'],
                'objeto_pi'    => $pi[0]['objeto_pi'],
                'descricao' => $pi[0]['descricao'],
                'rv'        => $pi[0]['rv'],
                'predios' => [
                    'codigo' => $pi[0]['predios'][0]['codigo'],
                    'name_predio' => $pi[0]['predios'][0]['name'],
                    'diretoria' => $pi[0]['predios'][0]['diretoria'],
                ],
                'items' => $pi[0]['items'],
                'qtde_vistorias_mes' => $pi[0]['qtde_vistorias_mes'],
                'numero_contrato' => $pi[0]['numero_contrato'],
                'numero_os' => $pi[0]['numero_os'],
                'nome_contratada' => $pi[0]['nome_contratada'],
                'numero_gestao_social' => $pi[0]['numero_gestao_social'],
            ];

        } else {

            $pi = [
                'id' => '',
                'codigo' => '',
                'dt_assinatura' => '',
                'id_predio' => '',
                'id_empreiteira' => '',
                'id_user' => '',
                'contratacao' => '',
                'programa' => '',
                'objeto_pi' => '',
                'valor_total' => '',
                'prazo_total' => '',
                'descricao' => '',
                'rv'        => '',
                'predios' => [
                    'codigo' => '',
                    'name_predio' => '',
                    'diretoria' => '',
                ],
                'items' => [
                    'id' => '',
                    'id_pi' => '',
                    'num_item' => '',
                    'tipo_item' => '',
                    'valor' => '',
                    'dt_assin_ois' => '',
                    'dt_abertura' => '',
                    'prazo' => '',
                    'descricao_item' => '',
                ],
                'numero_contrato' => '',
                'numero_os' => '',
                'qtde_vistorias_mes' => '',
                'nome_contratada' => '',
                'numero_gestao_social' => ''
            ];
        }

        return view('pi.store')
            ->with(compact('pi'))
            ->with(compact('fiscais'))
            ->with(compact('empreiteiras'))
            ->with(compact('oPi'))
            ->with(compact('programas'))
            ->with(compact('items'))
            ->with(compact('contratacao'));

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

    public function carregaEmpreiteiras($empreiteiras)
    {

        foreach ($empreiteiras as $empreiteira) {
            $return[$empreiteira['id']] = $empreiteira['name'];
        }

        return $return;
    }

    public function carregaFiscais($fiscais)
    {
        if ($fiscais) {
            foreach ($fiscais as $fiscal) {
                $return[$fiscal['id']] = $fiscal['name'];
            }
        }else{
            return "Nenhum usuário cadastrado como fiscal.";
        }
        return $return;
    }

    //FUNÇÕES DE CHAMADA PARA MODEL.

    public function programa(Programas $model, ProgramasRequest $request){
        $request->flash();
        $info = $request->all();
        $model->createProgramas($info['programa']);
        return true;
    }

    public function create(Pi $model, PiRequest $request)
    {
        $request->flash();
        $info = $request->all();

        if (isset($info['id'])) {
            try {
                $model->updatePi($info);
                return redirect()
                    ->back()
                    ->with('success', 'Processo de Intervenção Atualizado');
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        } else {
            try {
                $pi = $model->createPi($info);
                return redirect('pi/cadastro?id=' . $pi)
                    ->with('success', 'Processo de Intervenção Cadastrado');
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());

            }
        }
    }

    public function delete(Pi $model, $id)
    {
        try {
            $model->deletePi($id);
            return redirect()
                ->back()
                ->with('success', 'Processo de Intervenção DESATIVADO');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());

        }
    }
}
