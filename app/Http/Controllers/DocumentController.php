<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Pi;
use App\Models\Vistoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    private $model;

    public function __construct(Document $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $documents = $this->model->all();
        return view('documents.list', compact('documents'));
    }

    public function create()
    {
        return view('documents.store');
    }

    public function store(Request $request)
    {
        $info = [];
        $validate = Validator::make($request->all(), ['codigo' => 'required'],['required' => 'O código é obrigatorio']);
        $pi = $this->validateGenderDoc($request->except('_token'));
        $percentual = 0;
        $ultimaVistoria = Vistoria::where('codigo', $pi->codigo)->orderBy('id', 'DESC')->first();//Porcentagem Global
        if (!empty($ultimaVistoria)) {
            $percentual = $ultimaVistoria->avanco_fisico;
        }

        $this->calculateVistorias($pi);

        $info = [
            'nome_escola' => $pi->Predios[0]->name,
            'numero_os' => $pi->numero_os,
            'numero_gestao_social' => $pi->numero_gestao_social,
            'pi' => $pi->codigo,
            'codigo' => $pi->Predios[0]->codigo,
            'contrato' => $pi->numero_contrato,
            'nome_contratada' => $pi->nome_contratada,
            'qtde_vistorias_seguranca_original' => 0,
            'qtde_vistorias_gestao_original' => 0,
            'percentual' => $percentual,
            'justificativa' => null,
            'qtde_vistorias_complementar_obra' => 0,
            'qtde_vistorias_complementar_seguranca' => 0,
            'qtde_vistorias_complementar_gestao' => 0
        ];

        $this->model->store($info);
        return redirect()->back()->with('success', 'O Documento foi criado com sucesso');
    }
    public function show($id)
    {
        try {
            $document = Document::where('id', $id)->first();
            return \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,'defaultFont' => 'sans-serif'])->loadView('documents.os', compact('document'))->stream();
        } catch (Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
    public function edit(Request $request, $id)
    {
        try {
            $document = Document::where('id', $id)->first();
            $document->justificativa = $request->justificativa;
            $document->save();
            session()->flash('success', 'Documento editado com sucesso!');
            return response()->json(['success' => 'Documento editado com sucesso!'], 200);
        } catch (Exception $error) {
            session()->flash('error', $error->getMessage());
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
    public function pdf($id)
    {
        try {
            $document = Document::where('id', $id)->first();
            return \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,'defaultFont' => 'sans-serif'])->loadView('documents.os', compact('document'))->download();
        } catch (Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }
    private function validateGenderDoc($validateData)
    {
        $pi = Pi::where('codigo', $request->codigo)->with('Predios')->first();
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->getMessageBag());
        }
        if (empty($pi)) {
            return redirect()->back()->with('error', 'Código inexistente');
        }

        return $pi;
    }
    private function calculateVistorias($calculateVistoriaData)//Realiza o calculo de todas as vitorias para o documento OS
    {
        $qtdObras = DB::select("
            SELECT 
            fiscalizacao,
            seguranca,
            gestao
            FROM
            (
                SELECT 
                    (SELECT COUNT(*) FROM pis 
                    LEFT JOIN vistorias ON vistorias.pi_id = pis.id
                    LEFT JOIN vistorias_multiplas ON vistorias_multiplas.pi_id
                    WHERE pis.codigo = '".$calculateVistoriaData['codigo']."' AND vistorias.tipo_id IN('1','2','3')) AS fiscalizacao,	
                    (SELECT COUNT(*) FROM pis 
                    LEFT JOIN vistorias ON vistorias.pi_id = pis.id
                    LEFT JOIN vistorias_multiplas ON vistorias_multiplas.pi_id
                    WHERE pis.codigo = '".$calculateVistoriaData['codigo']."' AND vistorias_multiplas.tipo_id IN('8')) AS seguranca,
                    (SELECT COUNT(*) FROM pis 
                    LEFT JOIN vistorias ON vistorias.pi_id = pis.id
                    LEFT JOIN vistorias_multiplas ON vistorias_multiplas.pi_id
                    WHERE pis.codigo = '".$calculateVistoriaData['codigo']."' AND vistorias_multiplas.tipo_id IN('7')) AS gestao
                FROM pis WHERE pis.codigo = '".$calculateVistoriaData['codigo']."'
            ) AS obras
        "); //Retorna a quantidade de vistorias de obras

        
        /*
        
        PENSAR EM REGRA PARA CALCULAR VISTORIAS DE ST E GS do tipo de continuação.
        foreach ($calendarItens as $key => $item) {
            if ($item->diferenca_emDias < 0) {
                $calendarItens[$key]->qtde_previsao = null;
            } else {
                //Transformar qtde de dias em meses
                $meses = $item->diferenca_emDias / 30; // USANDO 30 DIAS como referencia para 1 mes
                $qtde_vistorias_mais = (int) round($meses * 4);
                $calendarItens[$key]->qtde_previsao = $qtde_vistorias_mais;
            }
            $calendarItens[$key]->total = $item->qtd_atual + $calendarItens[$key]->qtde_previsao;
            $calendarItens[$key]->saldo = $calendarItens[$key]->total - $item->total_enviadas;

            $itens[] = $calendarItens[$key];
        }*/
        return [
            'contratual' =>
            [
                'fiscalizacao' => $qtdObras->fiscalizacao,
                'segurancao' => $qtdObras->segurancao,
                'gestao' => $qtdObras->gestao
            ],
            'continuacao' => [
                'fiscalizacao' => $qtdCOntinuacao->fiscalizacao,
                'segurancao' => $qtqtdCOntinuacaodObras->segurancao,
                'gestao' => $qtdCOntinuacao->gestao
            ]
        ];
    }
}
