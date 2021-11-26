<?php

namespace App\Http\Controllers;

use App\Models\Vistoria;
use App\Models\VistoriasMultiplas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadZipRequest;
use Illuminate\Support\Facades\Log;
use App\Models\UploadLog;
use ZipArchive;

use DB;

class ZipArchiveMultiplosController extends Controller
{
    public function index()
    {
        return view('vistorias.ziparchive.multiplos.store');
    }

    public function descompactZip(UploadZipRequest $request)
    {
        dd("teste");
        $file = $request->file('zipArchive');
        $uploadLog = new UploadLog();
        $zip = new ZipArchive();
        $logs = null;
        DB::beginTransaction();
        if ($zip->open($file) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $archive = $zip->getNameIndex($i);
                $fileinfo = pathinfo($archive);
                $file = $zip->getFromName($fileinfo['basename']);
     
                //Verificar se existe a vistoria no banco
                $vistoria = VistoriasMultiplas::verifyIfVistoriaExists($fileinfo['filename']);
                if ($this->logs($vistoria, 'vistoria', $fileinfo) === false)continue;
                
                $filePath = $this->saveFileAndCreateDirectory($fileinfo['filename'], $file, $vistoria);
                $this->logs($filePath, 'filepath', $fileinfo);
                if ($this->logs($vistoria, 'vistoria', $fileinfo) === false)continue;
                $vistoria->update(['arquivo' => $filePath, 'status' => 'Aprovado']);
                DB::commit();
                $this->logs('', 'aprovado', $fileinfo);
            }
        }else {
            $this->logs($file, 'falha', $file);
            return redirect()->back();
        }
        return redirect()->back();
    }
    private function logs($data, $type, $fileinfo)
    {
        $uploadLog = new UploadLog();
        switch ( $data )
        {
            case !$data and $type == 'vistoria': //Vistorias não encontradas
                $logs = [
                    'user_id' => Auth()->user()->id,
                    'arquivo' => $fileinfo['filename'],
                    'status' => 'Vistoria não encontrada.',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                $uploadLog->storeLog($logs);
                session()->put('error', 'Existem vistorias que não foram encontradas !: '.$fileinfo['filename']);
                return false;
                break;
            case !$data and $type == 'filepath'://Nao foi possivel gravar no sistema
                $logs = [
                    'user_id' => Auth()->user()->id,
                    'arquivo' => $fileinfo['filename'],
                    'status' => 'Não foi possivel gravar arquivo no sistema.',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                Log::info('Não foi possivel gravar arquivo no sistema: '.$fileinfo['filename']);
                session()->put('error', 'Não foi possivel gravar arquivo no sistema: '.$fileinfo['filename']);
                return false;
                break;
            case !$data and $type == 'aprovado'://Vistorias Aprovadas
                $logs = [
                    'arquivo' => $fileinfo['filename'],
                    'status' => 'Aprovado',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                Log::info('Aprovado: '.$filePath);
                $uploadLog->storeLog($logs);
                return true;
                break;
            default://Erro ao abrir zip.
                $logs = [
                    'user_id' => Auth()->user()->id,
                    'arquivo' => 'LO_'.$fileinfo,
                    'status' => 'Falha ao abrir zip !',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                DB::reset();
                $uploadLog->storeLog($logs);
                flash()->put('error', 'Ocorreu um erro ao fazer o upload do arquivo!');
                break;
        }
        return true;
    }

    private function saveFileAndCreateDirectory($fileName, $file, $vistoria)
    {
        try {
            $dt_vistoria = date("d.m.y", strtotime($vistoria->dt_vistoria));
            $name_archive = '';
            switch ($vistoria->tipo_id) {
                case 4:
                    $name_archive = 'OS_' . str_replace('.', '', $vistoria->Predio->codigo) . '_' . $dt_vistoria . "_" . $vistoria->Fiscal->cod_user_fde;
                    $pastaName = 'orcamento_simples';
                    break;
                case 5:
                    $name_archive = 'OC_' . str_replace('.', '', $vistoria->Predio->codigo) . '_' . $dt_vistoria . "_" . $vistoria->Fiscal->cod_user_fde;
                    $pastaName = 'orcamento_complexo';
                    break;
                case 6:
                    $name_archive = 'RI_' . str_replace('.', '', $vistoria->Predio->codigo) . '_' . $dt_vistoria . "_" . $vistoria->Fiscal->cod_user_fde;
                    $pastaName = 'especificas';
                    break;
                case 7:
                    $name_archive = 'GS_' . str_replace('/', '.', $vistoria->Pi->codigo) . '_' . $dt_vistoria;
                    $pastaName = 'gestao_social';
                    break;
                case 8:
                    $name_archive = 'ST_' . str_replace('/', '.', $vistoria->Pi->codigo) . '_' . $dt_vistoria;
                    $pastaName = 'seguranca_trabalho';
                    break;
            }

            Storage::disk('public')->put("archives/{$pastaName}/{$name_archive}.pdf", $file);
            $filePath = "archives/{$pastaName}/{$name_archive}.pdf"; //VALIDAR NOME;

            return $filePath;
        } catch (Exception $e) {
            return false;
        }
    }
}
