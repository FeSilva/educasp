<?php

namespace App\Http\Controllers;

use App\Models\Vistoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadZipRequest;
use Illuminate\Support\Facades\Log;
use App\Models\UploadLog;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class ZipArchiveController extends Controller
{
    private $messages = [];

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
            if (empty($vistoria)) {
                //Logs caso a vistoria não tenha sido encontrada
                $this->logs($vistoria, 'vistoria', $fileinfo);
                continue;
            } else {
                $filePath = $this->saveFileAndCreateDirectory($fileinfo['filename'], $vistoria->pi->User[0]->cod_user_fde, $file);
                //Logs caso não seja possivel gravar o arquivo no sistema
                if (empty($filePath)) {
                    $this->logs($filePath, 'filepath', $fileinfo);
                    continue;
                }
                $vistoria->update(['arquivo' => $filePath, 'status' => 'Aprovado']);
                Log::info('Upload realizado com sucesso: '.$filePath);
                $this->logs(null, 'aprovado', $fileinfo);
            }
        }

        DB::commit();
        if (count($this->messages) > 0)
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
                $error[] = 'Existem vistorias que não foram encontradas !: '.$fileinfo['filename'];
                $this->messages[] = $error;
            break;
            case 'filepath'://Nao foi possivel gravar no sistema
                $logs = [
                    'user_id' => Auth()->user()->id,
                    'arquivo' => $fileinfo['filename'],
                    'status' => 'Não foi possivel gravar arquivo no sistema.',
                    'data_envio' => date('Y-m-d H:i:s')
                ];
                Log::info('Não foi possivel gravar arquivo no sistema chame o suporte técnico: '.$fileinfo['filename']);
                $error[] = 'Não foi possivel gravar arquivo no sistema: '.$fileinfo['filename'];
                $this->messages[] = $error;
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
