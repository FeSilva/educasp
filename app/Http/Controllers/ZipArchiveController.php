<?php

namespace App\Http\Controllers;

use App\Models\Vistoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadZipRequest;
use Illuminate\Support\Facades\Log;
use App\Models\UploadLog;
use ZipArchive;
use DB;

class ZipArchiveController extends Controller
{
    public function index()
    {
        return view('vistorias.ziparchive.store');
    }
    public function descompactZip(UploadZipRequest $request){
        $file = $request->file('zipArchive');
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
            $vistoria = Vistoria::verifyIfVistoriaExists($fileinfo['filename']);
            //Logs caso a vistoria não tenha sido encontrada
            $this->logs($vistoria, 'vistoria', $fileinfo);
            $filePath = $this->saveFileAndCreateDirectory($fileinfo['filename'], $vistoria->pi->User[0]->cod_user_fde, $file);
            //Logs caso não seja possivel gravar o arquivo no sistema
            $this->logs($filePath, 'filepath', $fileinfo);
            $vistoria->update(['arquivo' => $filePath, 'status' => 'Aprovado']);
            DB::commit();
            $this->logs('', 'aprovado', $fileinfo);
        }
        Log::info('Upload realizado com sucesso: '.$filePath);
        session()->flash('success', 'Upload Realizado com sucesso!');
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
                break;
            case !$data and $type == 'aprovado'://Vistorias Aprovadas
                $logs = [
                    'arquivo' => $fileinfo['filename'],
                    'status' => 'Aprovado',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                Log::info('Aprovado: '.$fileinfo['filename']);
                $uploadLog->storeLog($logs);
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
    }
    private function saveFileAndCreateDirectory($fileName, $codFde, $file)
    {
        try {
           // $newFileName = $fileName .= empty($codFde) ? '' : '_' . $codFde;
            $ano = substr($fileName, 18,2 );
            $date = substr($fileName, 10,5 ).".".$ano;
            $newFileName = substr($fileName, 0, 4) .".".  substr($fileName, 4, 5)."_".$date."_".$codFde;

            $fileName = substr($fileName, 0, 4) .  substr($fileName, 4, 5);
            Storage::disk('public')->put("archives/{$fileName}/LO_{$newFileName}.pdf", $file);
            $filePath = "archives/{$fileName}/LO_{$newFileName}.pdf"; //VALIDAR NOME;
            return $filePath;
        } catch (Exception $e) {
            return false;
        }
    }
}
