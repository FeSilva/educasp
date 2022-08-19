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
    private $messages = [];
    public function index()
    {
        return view('vistorias.ziparchive.multiplos.store');
    }

    public function descompactZip(UploadZipRequest $request)
    {
        $file = $request->file('zipArchive');
        $uploadLog = new UploadLog();
        $zip = new ZipArchive();
        $logs = null;
     
        DB::beginTransaction();
        if ($zip->open($file) === false) {
            $this->logs($file, 'falha', $file);
            return redirect()->back();
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $archive = $zip->getNameIndex($i);
            $fileinfo = pathinfo($archive);
            $file = $zip->getFromName($fileinfo['basename']);
            $vistoria = VistoriasMultiplas::verifyIfVistoriaExists($fileinfo['filename']);
            if (empty($vistoria)) {
                //Logs caso a vistoria não tenha sido encontrada
                $this->logs($vistoria, 'vistoria', $fileinfo);
                continue;
            } else {
                $filePath = $this->saveFileAndCreateDirectory($fileinfo['filename'], $file, $vistoria);
                //Logs caso não seja possivel gravar o arquivo no sistema

                if (empty($filePath)) {
                    $this->logs($filePath, 'filepath', $fileinfo);
                    continue;
                }
                $vistoria->update(['arquivo' => $filePath, 'status' => 'Aprovado']);
                Log::info('Upload realizado com sucesso: '.$filePath);
            }
            DB::commit();
            $this->logs('', 'aprovado', $fileinfo);
        }
        if (!empty($this->messages))
        {
            DB::rollBack();
            session()->flash('error', $this->messages);
        } else {
            session()->flash('success', 'Upload Realizado com sucesso!');
        }
        return redirect()->back();
    }
    private function logs($data, $type, $fileinfo)
    {
        $uploadLog = new UploadLog();
        switch ( $type )
        {
            case 'vistoria': //Vistorias não encontradas
                    $logs = [
                        'user_id' => Auth()->user()->id,
                        'arquivo' => $fileinfo['filename'],
                        'status' => 'Vistoria não encontrada.',
                        'data_envio' => date('Y-m-d H:i:s')
                    ];
                    $uploadLog->storeLog($logs);
                    $error = ['Existem vistorias que não existem no sistema, ou já foram aprovadas: '.$fileinfo['filename']];
                    $this->messages[] = $error;
                return $error;
                break;
            case 'filepath'://Nao foi possivel gravar no sistema
                $logs = [
                    'user_id' => Auth()->user()->id,
                    'arquivo' => $fileinfo['filename'],
                    'status' => 'Não foi possivel gravar arquivo no sistema.',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                Log::info('Não foi possivel gravar arquivo no sistema chame o suporte técnico: '.$fileinfo['filename']);
                $error= ['Não foi possivel gravar arquivo no sistema: '.$fileinfo['filename']];
                $this->messages[] = $error;
                return $error;
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
