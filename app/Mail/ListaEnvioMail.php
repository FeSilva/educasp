<?php

namespace App\Mail;

use App\Models\Vistoria;
use App\Models\EnvioEmail;
use App\Models\VistoriasMultiplas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\DB;

class ListaEnvioMail extends Mailable
{
    use Queueable, SerializesModels;

    private $vistorias;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vistorias, $lista_id, $codigo, $mes, $tipos, $vistoriasEnviadas = false)
    {
        $this->vistorias = $vistorias;
        $this->lista_id = $lista_id;
        $this->codigo   = $codigo;
        $this->mes = $mes;
        $this->tipos_vistorias = $tipos;
        $this->vistorias_enviadas = $vistoriasEnviadas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        $date = date("F", mktime(0, 0, 0, $this->mes, 10));
        $mes = ucfirst( utf8_encode( strftime("%B", strtotime($date))));
        $vistoriasEnviadas = $this->vistorias_enviadas;
        if($this->tipos_vistorias == 'SIMPLES'){
            $vistorias = Vistoria::whereIn('id', $this->vistorias)->get();

            $listaEnviada = DB::select(
                "SELECT vistoria.codigo as codigo_lista,
                        vistoria.id as id,
                        vistoria.dt_vistoria as data_envio,
                        vistoria.status as status,
                        vistoria.arquivo as arquivo
                        FROM vistorias AS vistoria
                        LEFT JOIN lista_vistoria_envios AS envios ON vistoria.id = envios.vistoria_id
                        LEFT JOIN lista_envios AS list ON list.id = envios.lista_id
                        WHERE list.id = ".$this->lista_id."        
                ");

            $seqE = explode('/',$this->codigo);
            $seq = $seqE[0];
    
            $email = $this
                        ->from('lo@cespfde.com.br')
                        ->subject('Vistorias de Fiscalização '.$mes.'-'.$seq)
                        ->to('lo.obras@fde.sp.gov.br')//Trocar email para um padrão
                        ->Bcc('maria.santos@cespfde.com.br')
                        ->Bcc('luiz.junior@cespfde.com.br')
                        ->view('emails.listaenvio' , compact('listaEnviada', 'seq', 'vistoriasEnviadas'));

            foreach ($vistorias as $vistoria) {
                $email->attach(storage_path("app/public/".$vistoria->arquivo));//colocar concatenação para storage apos excluir testes do BD
            }
        }

        if($this->tipos_vistorias == 'MULTIPLAS'){


            $vistorias = VistoriasMultiplas::whereIn('id', $this->vistorias)->with('Tipos')->get();

            if($vistorias[0]->Tipos->name == 'Específica'){
                $listaEnviada = DB::select(
                    "SELECT list.codigo_lista as codigo_lista,
                            vistoria.id as id,
                            vistoria.dt_vistoria as data_envio,
                            vistoria.status as status,
                            vistoria.arquivo as arquivo,
                            tipos.name as tipos_vistorias
                            FROM vistorias_multiplas AS vistoria
                            LEFT JOIN vistoria_tipos as tipos ON tipos.vistoria_tipo_id = vistoria.tipo_id
                            LEFT JOIN lista_vistoria_envios_multiplos AS envios ON vistoria.id = envios.vistoria_id
                            LEFT JOIN lista_envios_multiplos AS list ON list.id = envios.lista_id
                            WHERE list.id = ".$this->lista_id."        
                    ");

                $seqE = explode('/',$this->codigo);
                $seq = $seqE[0];
                $email = $this
                    ->from('lo@cespfde.com.br')
                    ->subject('Vistorias de '.$vistorias[0]->Tipos->name.' RIP - '.$mes.' - '.$seq)
                    ->to('lo.obras@fde.sp.gov.br')//Trocar email para um padrão
                    ->Bcc('maria.santos@cespfde.com.br')
                    ->Bcc('luiz.junior@cespfde.com.br')
                    ->view('emails.listaenvio' , compact('listaEnviada','seq', 'vistoriasEnviadas'));
            }else{
                $listaEnviada = DB::select(
                    "SELECT list.codigo_lista as codigo_lista,
                            vistoria.id as id,
                            vistoria.dt_vistoria as data_envio,
                            vistoria.status as status,
                            vistoria.arquivo as arquivo,
                            tipos.name as tipos_vistorias
                            FROM vistorias_multiplas AS vistoria
                            LEFT JOIN vistoria_tipos as tipos ON tipos.vistoria_tipo_id = vistoria.tipo_id
                            LEFT JOIN lista_vistoria_envios_multiplos AS envios ON vistoria.id = envios.vistoria_id
                            LEFT JOIN lista_envios_multiplos AS list ON list.id = envios.lista_id
                            WHERE list.id = ".$this->lista_id."        
                    ");

                $seqE = explode('/',$this->codigo);
                $seq = $seqE[0];
                $email = $this
                    ->from('lo@cespfde.com.br')
                    ->subject('Vistorias de '.$vistorias[0]->Tipos->name.' - '.$mes.' - '.$seq)
                    ->to('lo.obras@fde.sp.gov.br')//Trocar email para um padrão
                    ->Bcc('maria.santos@cespfde.com.br')
                    ->Bcc('luiz.junior@cespfde.com.br')
                    ->view('emails.listaenvio', compact('listaEnviada', 'seq', 'vistoriasEnviadas'));
            }
            foreach ($vistorias as $vistoria) {
                $email->attach(storage_path("app/public/".$vistoria->arquivo));//colocar concatenação para storage apos excluir testes do BD
            }
        }
        return $email;
    }
}
