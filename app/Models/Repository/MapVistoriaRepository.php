<?php
/**
 * ANCHOR Repository de mapeamento das vistorias.
 * ?Responsável por realizar o mapeamento de uma vistoria, seja ela multipla ou de fiscalização?
 * ?Buscando passo a passo de onde a vistoria pode estar?
 * @author Felipe Feitosa da Silva <felipe.silva@mostwebti.com.br>
 * --VERSION 1.0
 **/

namespace Model\Repository\MapVistorias;

/**Vistorias */
use App\Models\Vistoria as vistorias;
use App\Models\VistoriaMultiplas as multiplas;

//Protocolos
use App\Models\protocolo as protocolo;
use App\Models\protocoloMultiplos as protocoloMultiplos;

//Pivot protocolos/vistorias
use App\Models\protocoloVistorias as protocoloVistorias;
use App\Models\protocoloMultiplosVistorias as protocoloMultiplosVistorias;

use DB;

class MapVistoriaRepository
{
    /**
     * @var CostType
     */
    protected $model_vistorias;
    protected $model_vistorias_multiplas;
    protected $protocolos;
    protected $protocolosMultiplos;
    protected $pivotProtocolosVistorias;
    protected $pivotProtocolosMultiplasVistorias;
    

    public function __construct(
        vistorias $modelVistorias,
        multiplas $modelVistoriasMultiplas,
        protocolo $moodelProtocolos,
        protocolosMultiplos $moodelProtocolosMultiplos,
        pivotProtocolosVistorias $modelProtocolosVistorias,
        pivotProtocolosMultiplasVistorias $modelProtocolosMultiplasVistorias
    ) {
        //vistorias
        $this->model_vistorias = $modelVistoria;
        $this->model_vistorias_multiplas = $modelVistoriasMultiplas;

        //Protocolos
        $this->protocolos = $moodelProtocolos;
        $this->protocolosMultiplos = $moodelProtocolosMultiplos;

        //Pivot protocolos/vistorias
        $this->pivotProtocolosVistorias = $modelProtocolosVistorias;
        $this->pivotProtocolosMultiplasVistorias = $modelProtocolosMultiplasVistorias;
    }

    public function mapping($codigoVistoria)
    {
        //Realiza a primeira consulta na
        $this->model_vistorias->where('codigo', $codigoVistoria)->orderBy('id', asc)->get();
    }
}
