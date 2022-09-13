<?php

namespace App\Http\Controllers;

use App\Models\Protocolo;
use App\Models\ProtocolosMultiplos;
use App\Models\ProtocolosMultiplosVistoria;
use App\Models\Vistoria;
use App\Models\VistoriaTipo;
use App\Models\ProtocoloVistorias;
use App\Models\VistoriasMultiplas;
use Barryvdh\DomPDF\PDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\Tfpdf;
use PDFMerger;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProtocoloMultiplosController extends Controller
{
    private $model;

    public function __construct(Protocolo $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $protocolos = DB::select(
        "SELECT
                prt.id as id,
                prt.codigo AS cod_protocolo,
                DATE_FORMAT(prt.data,'%d/%m/%Y') AS data_protocolo,
                DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
                prt.status as status,
                tpv.name as tipo_protocolo,
                pv.vistoria_id,
                (SELECT COUNT(*) FROM vistorias_multiplas AS vis
                LEFT JOIN protocolosMultiplos_vistorias AS prtc ON prtc.vistoria_id = vis.id
                WHERE prtc.protocolo_id = prt.id)AS qtdVistorias
                FROM protocolos_multiplos prt
                LEFT JOIN protocolosMultiplos_vistorias pv ON pv.protocolo_id = prt.id
                LEFT JOIN vistorias_multiplas vt ON pv.vistoria_id = vt.id
                LEFT JOIN vistoria_tipos tpv on tpv.vistoria_tipo_id = prt.tipo_id
                GROUP BY cod_protocolo
        ");

        $vistorias = VistoriasMultiplas::where("status", "cadastro")->get();
        $qtdVistorias = count($vistorias);

        $aProtocolos = [];
        $count = 0;
        $countTwo = 1;

        foreach ($protocolos as $key => $protocolo) {
            $aProtocolos[] = [//criar primeiro registro
                'id' => $protocolo->id,
                'codigo' => $protocolo->cod_protocolo,
                'data' => $protocolo->data_protocolo,
                'tipo_protocolo' => $protocolo->tipo_protocolo,
                'total_vistoria' => $protocolo->qtdVistorias,
                'status' => $protocolo->status
            ];

        }

        return view('vistorias.protocolosenvios.multiplos.list', compact('aProtocolos', 'qtdVistorias'));
    }

    public function store(Request $request)
    {
        $ids = ['4', '5', '6', '7', '8'];
        $vistorias = VistoriasMultiplas::where("status", "cadastro")->get();
        $qtdVistorias = count($vistorias);
        $countSeq = ProtocolosMultiplos::select(DB::RAW('COUNT(id)+1 as seq'))->get();
        $tipos = VistoriaTipo::whereIn('vistoria_tipo_id', $ids)->get();
        $seq = null;

        switch ($countSeq[0]->seq) {
            case $countSeq[0]->seq < 10:
                $seq = '000' . $countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 9:
                $seq = '00' . $countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 100:
                $seq = '0' . $countSeq[0]->seq;
                break;
            case $countSeq[0]->seq > 999:
                $seq = $countSeq[0]->seq;
                break;
        }


        return view('vistorias.protocolosenvios.multiplos.store', compact('qtdVistorias', 'seq'), compact('tipos'));
    }

    public function edit(Request $request)
    {

        $protocolos = DB::select(
            "SELECT
                prt.id  as id,
                prt.codigo AS cod_protocolo,
                prt.data AS data_protocolo,
                prt.status as status,
                vt.cod_pi AS  cod_pis,
                vt.name_archive as name_archive,
                vt.id as vistoria_id,
                vt.merge as merge,
                vt.arquivo as arquivo,
                vt.cod_predio as cod_predio,
                DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
                vtp.name AS tipo_vistoria,
                vtp.vistoria_tipo_id as id_vistoria
                FROM protocolos_multiplos prt
                LEFT JOIN protocolosMultiplos_vistorias pv ON pv.protocolo_id = prt.id
                LEFT JOIN vistorias_multiplas vt ON pv.vistoria_id = vt.id
                LEFT JOIN vistoria_tipos vtp ON vtp.vistoria_tipo_id = vt.tipo_id
                WHERE prt.id = {$request->id} ORDER by dt_vistoria");

        $aProtocolo = [];

        foreach ($protocolos as $key => $protocolo) {
            $aProtocolo['protocolo'] = [
                'id'                => $protocolo->id,
                'cod_protocolo'     => $protocolo->cod_protocolo,
                'data_protocolo'    => $protocolo->data_protocolo,
                'status'            => $protocolo->status,
                'merge'             => $protocolo->merge,
            ];

            $aProtocolo['vistorias'][$key] = [
                'id'            => $protocolo->vistoria_id,
                'codigo'        => $protocolo->cod_pis,
                'name'          => $protocolo->name_archive,
                'dt_vistoria'   => $protocolo->dt_vistoria,
                'tipo'          => $protocolo->tipo_vistoria,
                'arquivo'       => $protocolo->arquivo,
            ];

            $aProtocolo['tipo_vistoria'] = $protocolo->id_vistoria;
        }

        return view('vistorias.protocolosenvios.multiplos.edit', compact('aProtocolo'));
    }

    public function pdf(Request $request)
    {
        $info = $request->all();

        $protocolos = DB::select(
            "SELECT
                prt.id  as id,
                prt.codigo AS cod_protocolo,
                prt.data AS data_protocolo,
                prt.status as status,
                vt.cod_pi AS  cod_pis,
                predios.codigo as cod_predio,
                predios.name AS predio_name,
                DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
                vtp.name AS tipo_vistoria
                FROM protocolos_multiplos prt
                LEFT JOIN protocolosMultiplos_vistorias pv ON pv.protocolo_id = prt.id
                LEFT JOIN vistorias_multiplas vt ON pv.vistoria_id = vt.id
                LEFT JOIN vistoria_tipos vtp ON vtp.vistoria_tipo_id = vt.tipo_id
                LEFT JOIN predios ON vt.predio_id = predios.id
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
                'dt_vistoria' => $protocolo->dt_vistoria,
                'tipo' => $protocolo->tipo_vistoria
            ];

        }

        return \PDF::loadView('vistorias.protocolosenvios.multiplos.pdf', compact('aProtocolo'))
            //p->setPaper('a4', 'landscape')
            ->download('protocolo_' . $aProtocolo['protocolo']['codigo'] . '.pdf');
    }


    public function AprovarVistoriasST(Request $request)
    {

        $info = $request->all();

        DB::beginTransaction();

        try {

            foreach ($info['id_vistoria'] as $id_vistoria) {
            
                if (isset($info['check_' . $id_vistoria]) and $info['check_' . $id_vistoria] == "on") {
                    $aVistoria = VistoriasMultiplas::where('id', $id_vistoria)->first();

                    $file = $request->file('protocolo_archive_st');

                    $pdf = new \setasign\Fpdi\Fpdi();

                    $pageCount2 = $pdf->setSourceFile('storage/' . $aVistoria->arquivo);
                    for ($i = 0; $i < $pageCount2; $i++) {
                        $tpl = $pdf->importPage($i + 1);
                        $pdf->addPage();
                        $pdf->useTemplate($tpl);
                    }

                    $pageCount = $pdf->setSourceFile($request->file('protocolo_archive_st')->path());
                    for ($i = 0; $i < $pageCount; $i++) {
                        $tpl = $pdf->importPage($i + 1);
                        $pdf->addPage();
                        $pdf->useTemplate($tpl);
                    }
                    date_default_timezone_set('UTC');
                    $dt_vistoria = date("d.m.y", strtotime($aVistoria['dt_vistoria']));
                    $name_archive = 'ST_'.str_replace("/",".",$aVistoria['cod_pi']).'_'.$dt_vistoria;
                    $pdf->Output( storage_path().'/app/public/archives/seguranca_trabalho/'.$name_archive.".pdf", "F");
                    VistoriasMultiplas::find($id_vistoria)->update(['status' => 'Aprovado', 'merge' => 1]);

                } else {
                    //Excluí a vistoria que foi desmarcada.. ela irá sumir, validar o que irá ocorre com status dela.
                    ProtocolosMultiplosVistoria::where('vistoria_id', $id_vistoria)->delete();
                }
            }

            $protocolos = DB::select(
                "SELECT
                            prt.id  as id,
                            prt.codigo AS cod_protocolo,
                            prt.data AS data_protocolo,
                            prt.status as status,
                            vt.cod_pi AS  cod_pis,
                            vt.name_archive as name_archive,
                            vt.id as vistoria_id,
                            vt.merge as merge,
                            vt.arquivo as arquivo,
                            vt.cod_predio as cod_predio,
                            DATE_FORMAT(vt.dt_vistoria,'%d/%m/%Y') AS dt_vistoria,
                            vtp.name AS tipo_vistoria,
                            vtp.vistoria_tipo_id as id_vistoria
                            FROM protocolos_multiplos prt
                            LEFT JOIN protocolosMultiplos_vistorias pv ON pv.protocolo_id = prt.id
                            LEFT JOIN vistorias_multiplas vt ON pv.vistoria_id = vt.id
                            LEFT JOIN vistoria_tipos vtp ON vtp.vistoria_tipo_id = vt.tipo_id
                            WHERE prt.id =" . $info['id']);


            $aProtocolo = [];

            foreach ($protocolos as $key => $protocolo) {
                $aProtocolo['protocolo'] = [
                    'id'                => $protocolo->id,
                    'cod_protocolo'     => $protocolo->cod_protocolo,
                    'data_protocolo'    => $protocolo->data_protocolo,
                    'status'            => $protocolo->status,
                    'merge'             => $protocolo->merge,
                ];

                $aProtocolo['vistorias'][$key] = [
                    'id'            => $protocolo->vistoria_id,
                    'codigo'        => $protocolo->cod_pis,
                    'name'          => $protocolo->name_archive,
                    'dt_vistoria'   => $protocolo->dt_vistoria,
                    'tipo'          => $protocolo->tipo_vistoria,
                    'arquivo'       => $protocolo->arquivo,
                ];

                $aProtocolo['tipo_vistoria'] = $protocolo->id_vistoria;
            }

            DB::commit();
            session()->flash('success','Arquivo inserido com sucesso !');
            return view('vistorias.protocolosenvios.multiplos.edit', compact('aProtocolo'));
        } catch (Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }


    public function create(Request $request)
    {
        $info = $request->all();

        $id_protocolo = ProtocolosMultiplos::create([
            'codigo' => $info['codigo'],
            'data' => $info['data'],
            'tipo_id' => $info['tipo_vistoria']
        ]);

        foreach ($info['id_vistoria'] as $id_vistoria) {
            if (isset($info['check_' . $id_vistoria]) and $info['check_' . $id_vistoria] == "on") {
                VistoriasMultiplas::find($id_vistoria)->update(['status' => 'em aprovacao']);
                ProtocolosMultiplosVistoria::create([
                    'protocolo_id' => $id_protocolo->id,
                    'vistoria_id' => $id_vistoria,
                ]);
            }
        }

        return redirect('protocolos/multiplos')
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
        $protocolo = ProtocolosMultiplos::where('id', $protocoloId)
            ->with('protocoloVistoriasMultiplas')
            ->first();
        $vistoriaIds = [];
        foreach ($protocolo->protocoloVistoriasMultiplas as $protocoloVistoria) {
            $vistoriaIds[] = $protocoloVistoria->vistoria_id;
        }

        $vistorias = VistoriasMultiplas::whereIn('id', $vistoriaIds)->get();


        foreach ($vistorias as $vistoria) {
            $vistoria->update(['status' => 'cadastro']);
        }

        $vistoria->save();

        foreach ($protocolo->protocoloVistoriasMultiplas as $protocoloVistoria) {
            $protocoloVistoria->delete();
        }

        $protocolo->delete();

        return redirect()->back()->with('success', 'Protocolo excluído com sucesso!');
    }

    function pdf_recreate($f)
    {

        $fileArray[]			= str_replace('.pdf', '_.pdf', $f);
        $output_old_Name	= $f;
        $output_new_name  = str_replace('.pdf', '_.pdf', $f);
        $cmd = '';

        foreach ($fileArray as $file) {
            shell_exec("gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=" .$file . str_replace('_.pdf', '.pdf', $file));
        }

        try {
            return $output_new_name;
        } catch (Exception $e) {
            return str_replace('.pdf', '_.pdf', $output_new_name);
        }
    }

    function pdfVersion($filename)
    {
        $fp = @fopen($filename, 'rb');

        if (!$fp) {
            return 0;
        }

        /* Reset file pointer to the start */
        fseek($fp, 0);
        /* Read 20 bytes from the start of the PDF */
        preg_match('/\d\.\d/', fread($fp, 20), $match);

        fclose($fp);

        if (isset($match[0])) {
            return $match[0];
        } else {
            return 0;
        }
    }
}
