<?php 
/**
 * ANCHOR Class resposivel pela comunicação entre o sistema e a tabela medição.
 * @author Felipe Feitosa da Silva
 * @version 1.0
 *
 */
namespace App\Models\Medicao;

use App\Models\Medicao\Medicao;
use App\Models\Vistoria;
use App\Models\Despesas\Despesa;
use App\Models\VistoriasMultiplas;
use App\Models\Medicao\MedicaoAnexos;
use DB;
use Exception;
use Auth;

class MedicaoRepository
{
    private $model;
    private $vistoria;
    private $vistoriaMultiplas;
    private $despesa;
    private $anexos;

    public function __construct(
        Medicao $model,
        Vistoria $vistoria,
        VistoriasMultiplas $vistoriaMultiplas,
        Despesa $despesa,
        MedicaoAnexos $anexos
    ) {
        $this->model = $model;
        $this->vistoria = $vistoria;
        $this->vistoriaMultiplas = $vistoriaMultiplas;
        $this->despesaModel = $despesa;
        $this->anexosModel = $anexos;
    }

    public function create($medicao)
    {
        foreach ($medicao as $campo => $value) {
            $this->validateFields($campo, $value);
        }
        $data = [
            'user_by' => Auth()->user()->id,
            'name' => $medicao['name'],
            'dt_ini' => $medicao['dt_inicio'],
            'dt_fim' => $medicao['dt_fim']
        ];
        return $this->model->create($data);
    }

    public function get()
    {
        return $this->model->orderBy('medicao_id', 'desc')->get();
    }
    
    public function find($medicao_id)
    {
        return $this->model->find($medicao_id);
    }

    public function update($id, $data)
    {
        return $this->model->where("medicao_id", $id)->update($data);
    }

    public function validateFields($campo, $value)
    {
        if (empty($value)) {
            switch ($campo) {
                case 'name':
                    throw new Exception("Nome do Periodo não pode estar vazio");
                    break;
                case 'dt_inicio':
                    throw new Exception("Data de Inicio do Periodo não pode estar vazia");
                    break;
                case 'dt_fim':
                    throw new Exception("Data Fim do periodo não pode estar vazia");
                    break;
            }
        }
    }


    public function medirVistoria($medicao_id, $vistoria_id, $tipo_id)
    {
        if ($tipo_id == 1 or $tipo_id == 2 or $tipo_id == 3) {
            $this->vistoria->find($vistoria_id)->update(['medicao_id' => $medicao_id]);
        } else {
            $this->vistoriaMultiplas->find($vistoria_id)->update(['medicao_id' => $medicao_id]);
        }

        return true;
    }

    /**
     * Medição Fiscal
     * !REFACTOR!
     */
    public function medicaoFiscal($medicao, $medicao_id, $fiscal_id)
    {
        return DB::select("	SELECT 
		fiscal_id,
		vistoria_tipo,
		tipo_id,
		TotalMedido,
		ValorMedido,
		TotalDisponivel,
        valorDisponivel,
		TotalPendentes,
		valorPendentes
		FROM (	
            SELECT 
            users.id AS fiscal_id,
            vistoria_tipos.name AS vistoria_tipo,
            vistorias.tipo_id,
            SUM(
	            CASE
	                when medicao.medicao_id = '$medicao_id'and vistorias.medicao_id = '$medicao_id' then 1 
	                ELSE 0
	            END) AS TotalMedido,
	            SUM(
                CASE 
                    WHEN medicao.medicao_id = '$medicao_id' and vistorias.medicao_id = '$medicao_id' THEN  vistoria_tipos.price
                    ELSE 0
                end) AS ValorMedido,
                SUM(
            CASE
                when (vistorias.medicao_id IS NULL OR vistorias.medicao_id  = 0) AND vistorias.dt_vistoria   <= '$medicao[dt_fim]' AND vistorias.status IN ('Aprovado','Enviado')and vistorias.tipo_id = vistoria_tipos.vistoria_tipo_id  then 1 
                ELSE 0
            END) AS TotalDisponivel,
            SUM(
                CASE 
                    WHEN (vistorias.medicao_id IS NULL OR vistorias.medicao_id = 0) AND vistorias.dt_vistoria  <= '$medicao[dt_fim]' AND vistorias.status IN ('Aprovado','Enviado') and vistorias.tipo_id = vistoria_tipos.vistoria_tipo_id  THEN  vistoria_tipos.price
                    ELSE 0
                end) AS valorDisponivel,
             SUM(CASE
                when (vistorias.medicao_id IS NULL OR vistorias.medicao_id  = 0) AND vistorias.dt_vistoria  <= '$medicao[dt_fim]'  AND vistorias.status NOT IN ('Aprovado','Enviado') and vistorias.tipo_id = vistoria_tipos.vistoria_tipo_id then 1 
                ELSE 0
            END) AS TotalPendentes,
            SUM(
                CASE 
                    WHEN (vistorias.medicao_id IS NULL OR vistorias.medicao_id = 0) AND vistorias.dt_vistoria  <= '$medicao[dt_fim]' AND vistorias.status NOT IN ('Aprovado','Enviado')  AND vistorias.tipo_id = vistoria_tipos.vistoria_tipo_id THEN  vistoria_tipos.price
                    ELSE 0
                end) AS valorPendentes
            FROM vistorias
            INNER JOIN users ON users.id = vistorias.cod_fiscal_pi
            INNER JOIN vistoria_tipos ON vistoria_tipos.vistoria_tipo_id = vistorias.tipo_id
            LEFT JOIN medicao ON medicao.medicao_id = vistorias.medicao_id
            WHERE vistorias.cod_fiscal_pi = '$fiscal_id'
            GROUP BY tipo_id
            
			UNION
			
				SELECT 
            users.id AS fiscal_id,
            vistoria_tipos.name AS vistoria_tipo,
            vistorias_multiplas.tipo_id,
            SUM(
	            CASE
	                when medicao.medicao_id = '$medicao_id' and vistorias_multiplas.medicao_id = '$medicao_id' then 1 
	                ELSE 0
	            END) AS TotalMedido,
	            SUM(
                CASE 
                    WHEN medicao.medicao_id = '$medicao_id' and vistorias_multiplas.medicao_id = '$medicao_id' THEN  vistoria_tipos.price
                    ELSE 0
                end) AS ValorMedido,
                SUM(
            CASE
                when (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id  = 0) AND vistorias_multiplas.dt_vistoria  <= '$medicao[dt_fim]' AND vistorias_multiplas.status IN ('Aprovado','Enviado')and vistorias_multiplas.tipo_id = vistoria_tipos.vistoria_tipo_id  then 1 
                ELSE 0
            END) AS TotalDisponivel,
            SUM(
                CASE 
                    WHEN (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]' AND vistorias_multiplas.status IN ('Aprovado','Enviado') and vistorias_multiplas.tipo_id = vistoria_tipos.vistoria_tipo_id  THEN  vistoria_tipos.price
                    ELSE 0
                end) AS valorDisponivel,
             SUM(CASE
                when (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id  = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]'  AND vistorias_multiplas.status NOT IN ('Aprovado','Enviado') and vistorias_multiplas.tipo_id = vistoria_tipos.vistoria_tipo_id then 1 
                ELSE 0
            END) AS TotalPendentes,
            SUM(
                CASE 
                    WHEN (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]' AND vistorias_multiplas.status NOT IN ('Aprovado','Enviado')  AND vistorias_multiplas.tipo_id = vistoria_tipos.vistoria_tipo_id THEN  vistoria_tipos.price
                    ELSE 0
                end) AS valorPendentes
            FROM vistorias_multiplas
            INNER JOIN users ON users.id = vistorias_multiplas.fiscal_user_id
            INNER JOIN vistoria_tipos ON vistoria_tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
            LEFT JOIN medicao ON medicao.medicao_id = vistorias_multiplas.medicao_id
            WHERE vistorias_multiplas.fiscal_user_id = '$fiscal_id'
            GROUP BY tipo_id
         ) AS t");
    }


    public function relatoryMedicoes($medicao_id, $fiscal_id)
    {
        $vistorias = DB::select("SELECT 
            tipos.vistoria_tipo_id as tipo_id,
            tipos.name AS tipo,
            pis.codigo AS codigo_pi,
            predios.name AS name_predio,
            vistorias.dt_vistoria AS data_Vistoria,
            tipos.price as amount
            FROM vistorias
            INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
            INNER JOIN pis ON pis.id = vistorias.pi_id
            INNER JOIN predios ON predios.id = pis.id_predio
            WHERE 
            medicao_id = '$medicao_id'
            AND
            cod_fiscal_pi = '$fiscal_id'
    
            UNION 
            
            SELECT 
            tipos.vistoria_tipo_id as tipo_id,
            tipos.name AS tipo,
            pis.codigo AS codigo_pi,
            predios.name AS name_predio,
            vistorias_multiplas.dt_vistoria AS data_Vistoria,
            tipos.price as amount
            FROM vistorias_multiplas
            INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
            LEFT JOIN pis ON pis.id = vistorias_multiplas.pi_id
            INNER JOIN predios ON (predios.id = pis.id_predio OR predios.id = vistorias_multiplas.predio_id)
            WHERE medicao_id = '$medicao_id' AND fiscal_user_id = '$fiscal_id'
        ");

        $return = [];
        $tipos = 0;
        foreach ($vistorias as $key => $vistoria) {
            $return[$vistoria->tipo]['values'][] = [
                'tipo' => $vistoria->tipo,
                'codigo' => $vistoria->codigo_pi,
                'predio' => $vistoria->name_predio,
                'data_vistoria' => $vistoria->data_Vistoria,
                'amount' => $vistoria->amount
            ];
        }
        return $return;
    }

    public function relatoryDespesas($medicao_id, $fiscal_id)
    {
        //Vistorias realiadas!
        $vistorias_realizadas =  DB::select("SELECT 
        vistorias.codigo AS codigo,
        predios.name AS predio_name,
        tipos.name AS tipo_vistoria,
        tipos.price as amount,
        date_format(vistorias.dt_vistoria,'%d/%m/%Y') AS data_vistoria
        FROM vistorias 
        INNER JOIN pis ON pis.id = vistorias.pi_id
        INNER JOIN predios ON predios.id = pis.id_predio
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
        WHERE medicao_id = '$medicao_id' AND cod_fiscal_pi = '$fiscal_id'
        
        UNION
        
        SELECT 
        vistorias_multiplas.cod_pi AS codigo,
        predios.name AS predio_name,
        tipos.name AS tipo_vistoria,
        tipos.price as amount,
        date_format(vistorias_multiplas.dt_vistoria,'%d/%m/%Y') AS data_vistoria
        FROM vistorias_multiplas 
        LEFT JOIN pis ON pis.id = vistorias_multiplas.pi_id
        LEFT JOIN predios ON predios.id = vistorias_multiplas.predio_id
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
        WHERE medicao_id =  '$medicao_id'  AND fiscal_user_id = '$fiscal_id'
        ");
        $despesas = $this->despesaModel->where("medicao_id", $medicao_id)->where("fiscal_id", $fiscal_id)->get();
        return [
            'realizadas' => $vistorias_realizadas,
            'despesas' => $despesas
        ];
    }

    public function saveFile($data, $file, $fiscal)
    {
        $filename = str_replace(' ', '', $data['name_archive']);
        $this->anexosModel->create([
            'medicao_id' => $data['medicao_id'],
            'fiscal_id' => $data['fiscal_id'],
            'name' => $data['name_archive'],
            'size' => $file,
            'amount' => $data['amount'],
            'path' => "storage/archives/medicao/{$data['medicao_id']}/fiscal/{$data['fiscal_id']}/{$filename}.pdf"
        ]);
        return "storage/archives/medicao/{$data['medicao_id']}/fiscal/{$data['fiscal_id']}/";
    }

    public function getAnexos($medicao_id, $fiscal_id)
    {
        return $this->anexosModel->where("medicao_id", $medicao_id)->where("fiscal_id", $fiscal_id)->get();
    }
}
