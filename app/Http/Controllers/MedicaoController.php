<?php

namespace App\Http\Controllers;

use App\Http\Services\MedicaoService;
use Illuminate\Http\Request;

use Exception;

class MedicaoController extends Controller
{

    public function __construct(MedicaoService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicoes = $this->service->index();
        return view("medicao.index", compact('medicoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("medicao.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $return = $this->service->store($request->except('_token'));
            return session()->flash('success', 'Medição Cadastrada com sucesso');
        } catch (\Exception $e) {
            return session()->flash('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($medicao_id)
    {
        $medicaoFiscais = $this->service->show($medicao_id);
        $medicao = $this->service->getMedicao($medicao_id);
        return view("medicao.show", compact('medicaoFiscais', 'medicao'));
    }

    /**
     * Route medicao/id/fiscal/id/show
     * Busca os detalhes das vistorias medidas com base na medicao e no fiscal.
     * @return $medicao, $fiscalDetalhes, $fiscal
     */

    public function medicaoFiscal($medicao_id, $fiscal_id, $status)
    {
        try {
            $medicao = $this->service->getMedicao($medicao_id);
            $fiscalDetalhes = $this->service->fiscalDetails($medicao, $medicao_id, $fiscal_id);
    
            $fiscal = $this->service->getFiscal($fiscal_id);
            $despesas = $this->service->listDespesas($medicao_id, $fiscal_id);
            $anexos = $this->service->listAnexos($medicao_id, $fiscal_id);
            return view("medicao.fiscal.show", compact('fiscalDetalhes', 'medicao', 'fiscal', 'despesas', 'anexos', 'status'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Carrega modals de vistorias Medidas, disponiveis e pendentes.
     */
    public function vistoriaDetails(Request $request)
    {
        $medicao = $this->service->getMedicao($request->post("medicao_id"));
        $medidas = $this->service->vistoriasMedidas(
            $medicao,
            $request->post("medicao_id"),
            $request->post("fiscal_id"),
            $request->post("tipo_id")
        );

        $disponiveis = $this->service->VistoriasDisponiveis(
            $medicao,
            $request->post("fiscal_id"),
            $request->post("tipo_id")
        );

        $pendentes = $this->service->VistoriasPendentes(
            $medicao,
            $request->post("fiscal_id"),
            $request->post("tipo_id")
        );

        return [
            'medidas' => $medidas,
            'pendentes' => $pendentes,
            'disponiveis' => $disponiveis
        ];
    }

    /**
     * Função Responsável por realizar a medição de uma vistoria, atrelando ela a medição existente.
     * 
     */
    public function medirVistoria(Request $request)
    {
        $vistoria_id = $request->post("vistoria_id");
        $tipo_id = $request->post("tipo_id");
        $medicao_id = $request->post("medicao_id");

        $this->service->medirVistoria($medicao_id, $vistoria_id, $tipo_id);
        
        return $vistoria_id;
    }

    /**
     * Função responsável pelo retorno de vistorias disponiveis para medição.
     * 
     */
    public function listVistoriasDespesas(Request $request)
    {
        try {
            if ($request->post('despesa_id') != null) {
                $vistoriasDespesasList = $this->service->vistoriasDespesasList(
                    $request->post("dt_recibo"),
                    $request->post("medicao_id"),
                    $request->post("fiscal_id"),
                    $request->post("despesa_id")
                );


                $dt_recibo = date_create($vistoriasDespesasList[0]->dt_vistoria);
                $vistoriasDespesasDisponivelList = $this->service->vistoriasDespesasDisponivelList(
                    date_format($dt_recibo, 'Y-d-m'),
                    $request->post("medicao_id"),
                    $request->post("fiscal_id"),
                );
                return [
                    'modal' => true,
                    'vistoriasDespesas' => $vistoriasDespesasList,
                    'vistoriasDespesasDisponiveis' => $vistoriasDespesasDisponivelList
                ];
            } else {
                $vistoriasDespesasListFiscalizacao = $this->service->vistoriasDespesasList(
                    $request->post("dt_recibo"),
                    $request->post("medicao_id"),
                    $request->post("fiscal_id")
                );
            }
            return $vistoriasDespesasListFiscalizacao;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * Função responsável pelo retorno de vistorias disponiveis para medição.
     *
     */
    public function createVistoriasDespesas(Request $request)
    {
        try {
            $data = $request->all();
            return $this->service->createDespesa($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Função responsável por atualizar uma despesa (Retirar vistorias atreladas)
     *
     * @param
     **/
    public function updateVistoriasDespesas(Request $request)
    {
        try {
            $this->service->updateDespesa($request->except("_token"));
            return redirect("/medicao/show/{$request->post('medicao_update_id')}/fiscal/{$request->post('fiscal_id')}/show")->with('success', 'Despesa Atualizada com Sucesso !!');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Medicao\Medicao  $medicao
     * @return \Illuminate\Http\Response
     */
    public function edit($medicao_id)
    {
       //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicao\Medicao  $medicao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicao $medicao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicao\Medicao  $medicao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicao $medicao)
    {
        //
    }

    public function relatoryMedicoes($fiscal_id, $medicao_id)
    {
        try {
            $medicoesDetalhes = $this->service->returnTableInReport($medicao_id, $fiscal_id);

            //return \PDF::loadView('medicao.fiscal.relatorio.medicoes', compact('medicoesDetalhes'))->stream();
            $company = $this->service->companyFiscal($fiscal_id);
            $medicao = $this->service->getMedicao($medicao_id);

            return \PDF::loadView('medicao.fiscal.relatorio.medicoes', compact('medicoesDetalhes', 'company', 'medicao'))
            //p->setPaper('a4', 'landscape')
            ->download('relatorio_medicao.pdf');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    public function relatoryDespesas($fiscal_id, $medicao_id)
    {
        $despesasDetalhes = $this->service->returnTableInReport($medicao_id, $fiscal_id, true);
       
        foreach ($despesasDetalhes as $detalhes) {
            foreach ($detalhes as $types => $values) {
                $listagem[$types] = $values;
            }
        }
        $medicao = $this->service->getMedicao($medicao_id);
        $fiscal = $this->service->getFiscal($fiscal_id);
        return \PDF::loadView('medicao.fiscal.relatorio.despesas', compact('listagem', 'medicao', 'fiscal'))
            //p->setPaper('a4', 'landscape')
            ->download('relatorio_despesas.pdf');
    }

    public function createAnexos(Request $request)
    {
        try {
            $data = $request->all();
            $file = $request->file('archive');
            $this->service->saveFile($data, $file);
            return redirect("/medicao/show/{$data['medicao_id']}/fiscal/{$data['fiscal_id']}/show")->with('success', 'Anexos Cadastrado com Sucesso');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function atualizarStatusMedicaoFiscais(Request $request)
    {
        return $this->service->atualizarStatusMedicaoFiscais($request->except("_token"));
    }
}
