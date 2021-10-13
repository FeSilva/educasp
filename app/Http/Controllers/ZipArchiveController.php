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
    public function index(){
        return view('vistorias.ziparchive.store');
    }

    public function descompactZip(UploadZipRequest $request){
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
                $vistoria = Vistoria::verifyIfVistoriaExists($fileinfo['filename']);


                if (!$vistoria) {
                    $logs = [
                        'user_id' => Auth()->user()->id,
                        'arquivo' => $file,
                        'status' => 'Vistoria não encontrada.',
                        'data_envio' => date('Y-m-d H:i:s')
                    ];
                    $uploadLog->storeLog($logs);
                    Log::info('Existem vistorias que não foram encontradas !: '.$fileinfo['filename']);
                    session()->put('error', 'Existem vistorias que não foram encontradas !: '.$fileinfo['filename']);
                    continue;
                }

                $filePath = $this->saveFileAndCreateDirectory($fileinfo['filename'], $vistoria->pi->User[0]->cod_user_fde, $file);

                if (!$filePath) {

                    $logs = [
                        'user_id' => Auth()->user()->id,
                        'arquivo' => $file,
                        'status' => 'Não foi possivel gravar arquivo no sistema.',
                        'data_envio' => date('Y-m-d H:i:s')
                    ];

                    Log::info('Não foi possivel gravar arquivo no sistema: '.$fileinfo['filename']);
                    session()->put('error', 'Não foi possivel gravar arquivo no sistema: '.$fileinfo['filename']);
                    $uploadLog->storeLog($logs);
                    return redirect()->back();
                }

                $vistoria->update(['arquivo' => $filePath, 'status' => 'Aprovado']);

                DB::commit();
               /* if(!isset($logs)){
                    $logs = [
                        'arquivo' => $fileinfo['filename'],
                        'status' => 'Aprovado',
                        'data_envio' => date('Y-m-d H:i:s')
                    ];

                    Log::info('Aprovado: '.$filePath);
                    $uploadLog->storeLog($logs);
                }
                */
            }

            Log::info('Upload realizado com sucesso: '.$filePath);
            session()->flash('success', 'Upload Realizado com sucesso!');

        } else {

            $logs = [
                'user_id' => Auth()->user()->id,
                'arquivo' => 'LO_'.$file,
                'status' => 'Falha ao abrir zip !',
                'data_envio' => date('Y-m-d H:i:s')
            ];

            DB::reset();
            $uploadLog->storeLog($logs);
            session()->put('error', 'Ocorreu um erro ao fazer o upload do arquivo!');
        }

        return redirect()->back();
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
