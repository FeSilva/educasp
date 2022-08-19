<?php

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\Vistoria;
use App\Models\ProtocoloVistorias;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProtocoloController extends Controller
{
    private $model;

    public function __construct(Protocolo $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $protocolos = DB::select("SELECT
            prt.id as id,
            prt.codigo AS cod_protocolo,
            DATE_FORMAT(prt.data,'%d/%m/%Y') AS data_protocolo,
            DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
            vt.avanco_fisico AS avanco_fisico,
            prt.status as status,
            pv.vistoria_id,
            (SELECT COUNT(*) FROM vistorias AS vis
            LEFT JOIN protocolo_vistorias AS prtc ON prtc.vistoria_id = vis.id
            WHERE prtc.protocolo_id = prt.id)AS qtdVistorias
            FROM protocolos prt
            LEFT JOIN protocolo_vistorias pv ON pv.protocolo_id = prt.id
            LEFT JOIN vistorias vt ON pv.vistoria_id = vt.id
            GROUP BY cod_protocolo
        ");


        $vistorias = Vistoria::where("status", "cadastro")->get();
        $qtdVistorias = count($vistorias);

        $aProtocolos = [];
        $count = 0;
        $countTwo = 1;

        foreach ($protocolos as $key => $protocolo) {

                $aProtocolos[] = [//criar primeiro registro
                    'id' => $protocolo->id,
                    'codigo' => $protocolo->cod_protocolo,
                    'data' => $protocolo->data_protocolo,
                    'avanco_fisico' => $protocolo->avanco_fisico,
                    'total_vistoria' => $protocolo->qtdVistorias,
                    'status' => $protocolo->status
                ];

        }


        return view('vistorias.protocolosenvios.list', compact('aProtocolos', 'qtdVistorias'));
    }

    public function store(Request $request)
    {

        $vistorias = Vistoria::where("status", "cadastro")->get();
        $qtdVistorias = count($vistorias);
        $countSeq =  Protocolo::select(DB::RAW('COUNT(id)+1 as seq'))->get();
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
        }



        return view('vistorias.protocolosenvios.store', compact('qtdVistorias','seq'));
    }

    public function edit(Request $request)
    {

        $protocolos = DB::select(
            "SELECT
                prt.id  as id,
                prt.codigo AS cod_protocolo,
                prt.data AS data_protocolo,
                prt.status as status,
                pis.codigo AS  cod_pis,
                predios.name AS predio_name,
                DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
                vt.avanco_fisico AS avanco_fisico,
                vtp.name AS tipo_vistoria
                FROM protocolos prt
                LEFT JOIN protocolo_vistorias pv ON pv.protocolo_id = prt.id
                LEFT JOIN vistorias vt ON pv.vistoria_id = vt.id
                LEFT JOIN vistoria_tipos vtp ON vtp.vistoria_tipo_id = vt.tipo_id
                LEFT JOIN pis  ON vt.pi_id = pis.id
                LEFT JOIN predios ON pis.id_predio = predios.id
                WHERE prt.id =" . $request->id);

        $aProtocolo = [];


        foreach ($protocolos as $key => $protocolo) {
            $aProtocolo['protocolo'] = [
                'id' => $protocolo->id,
                'cod_protocolo' => $protocolo->cod_protocolo,
                'data_protocolo' => $protocolo->data_protocolo,
                'status' => $protocolo->status
            ];

            $aProtocolo['vistorias'][$key] = [
                'codigo' => $protocolo->cod_pis,
                'predio' => $protocolo->predio_name,
                'avanco_fisico' => $protocolo->avanco_fisico,
                'dt_vistoria' => $protocolo->dt_vistoria,
                'tipo' => $protocolo->tipo_vistoria
            ];
        }


        return view('vistorias.protocolosenvios.edit', compact('aProtocolo'));
    }

    public function pdf(Request $request)
    {
        $info = $request->all();

        $protocolos = DB::select(
            "SELECT
                prt.codigo AS cod_protocolo,
                DATE_FORMAT(prt.data,'%d/%m/%Y') AS data_protocolo,
                pis.codigo AS  cod_pis,
                predios.codigo as cod_predio,
                predios.name AS predio_name,
                DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
                vt.avanco_fisico AS avanco_fisico,
                vtp.name AS tipo_vistoria
                FROM protocolos prt
                INNER JOIN protocolo_vistorias pv ON pv.protocolo_id = prt.id
                INNER JOIN vistorias vt ON pv.vistoria_id = vt.id
                INNER JOIN vistoria_tipos vtp ON vtp.vistoria_tipo_id = vt.tipo_id
                INNER JOIN pis  ON vt.pi_id = pis.id
                INNER JOIN predios ON pis.id_predio = predios.id
                WHERE prt.id =" . $request->id);

        $aProtocolo = [];
        foreach ($protocolos as $key => $protocolo) {
            $aProtocolo['protocolo'] = [
                'codigo' => $protocolo->cod_protocolo,
                'data' => $protocolo->data_protocolo,
            ];

            $aProtocolo['vistorias'][$key] = [
                'codigo' => $protocolo->cod_pis,
                'codigo_predio' => $protocolo->cod_predio,
                'predio' => $protocolo->predio_name,
                'avanco_fisico' => $protocolo->avanco_fisico,
                'dt_vistoria' => $protocolo->dt_vistoria,
                'tipo' => $protocolo->tipo_vistoria
            ];

        }

        return \PDF::loadView('vistorias.protocolosenvios.pdf', compact('aProtocolo'))->stream();
            //p->setPaper('a4', 'landscape')
            //->download('protocolo_' . $aProtocolo['protocolo']['codigo'] . '.pdf');
    }


    public function create(Request $request)
    {
        $info = $request->all();

        $id_protocolo = Protocolo::create([
            'codigo' => $info['codigo'],
            'data' => $info['data']
        ]);

        foreach ($info['id_vistoria'] as $id_vistoria) {
            if (isset($info['check_' . $id_vistoria]) and $info['check_' . $id_vistoria] == "on") {
                Vistoria::find($id_vistoria)->update(['status' => 'em aprovação']);
                $protocolos = ProtocoloVistorias::create([
                    'protocolo_id' => $id_protocolo->id,
                    'vistoria_id' => $id_vistoria,
                ]);
            }
        }


        return redirect('protocolos')
            ->with('success', 'Protocolo Gerado com sucesso !');
    }

    public function update(Request $request, $protocoloId)
    {
        $protocolo = $this->model->findOrFail($protocoloId);
        try {
            $protocolo->status = '1';
            $protocolo->update();
            return redirect()->back()->with('success', 'Protocolos enviados com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao enviar os protocolos');
        }
    }

    /**
     * Deletar um protocolo de envio
     */
    public function destroy($protocoloId)
    {
        $protocolo = $this->model->where('id', $protocoloId)
            ->with('protocoloVistorias')
            ->first();

        $vistoriaIds = [];
        foreach ($protocolo->protocoloVistorias as $protocoloVistoria) {
            $vistoriaIds[] = $protocoloVistoria->vistoria_id;
        }

        $vistorias = Vistoria::whereIn('id', $vistoriaIds)->get();
        foreach ($vistorias as $vistoria) {
            $vistoria->update(['status' => 'cadastro']);
        }

        foreach ($protocolo->protocoloVistorias as $protocoloVistoria) {
            $protocoloVistoria->delete();
        }
        $protocolo->delete();

        return redirect()->back()->with('success', 'Protocolo excluído com sucesso!');
    }
}
