<?php 

namespace App\Http\Services;


use App\Models\Medicao\MedicaoRepository;
use App\Models\Vistoria;
use App\Models\VistoriasMultiplas;
use App\Models\CompanyUsers;

use App\Models\Medicao\MedicaoFiscais;
use App\Models\Users;

//Despesas
use App\Models\Despesas\Despesa;
use App\Models\Despesas\Vistorias as DespesasVistorias;

use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Auth;
use File;

use DB;
use Exception;

class MedicaoService
{
    private $repository;
    private $vistoriaModel;
    private $vistoriaMultiplasModel;
    private $medicaoFiscaisModel;
    private $despesasVistoriasModel;
    private $despesasModel;
    private $users;
    private $company;

    public function __construct(
        MedicaoRepository $repository,
        Vistoria $vistoriaModel,
        VistoriasMultiplas $vistoriaMultiplasModel,
        MedicaoFiscais $medicaoFiscaisModel,
        Users $users,
        DespesasVistorias $despesasVistoriasModel,
        Despesa $despesasModel,
        CompanyUsers $company
    ) {
        $this->repository = $repository;
        $this->vistoriaModel = $vistoriaModel;
        $this->vistoriaMultiplasModel = $vistoriaMultiplasModel;
        $this->medicaoFiscaisModel = $medicaoFiscaisModel;
        $this->users = $users;
        $this->companyModel = $company;

        //Despesas

        $this->despesasVistoriasModel = $despesasVistoriasModel;
        $this->despesasModel = $despesasModel;
    }

    public function index()
    {
        $medicoes = $this->repository->get();
        if (count($medicoes) > 0) {
            foreach ($medicoes as $medicao) {
                $qtdTotal = 0;
                $vistoria = $this->vistoriaModel->where("medicao_id", $medicao->medicao_id)->count();
                $vistoriaMultiplas = $this->vistoriaMultiplasModel->where("medicao_id", $medicao->medicao_id)->count();
              
                $qtdTotal = $vistoria + $vistoriaMultiplas;
                $return[] = [
                    'medicao'=> $medicao,
                    'qtd_total' => $qtdTotal
                ];
            }
            return $return;
        }
        return [];
    }

    public function store($medicao)
    {
        //Criar Medicação & Retorna ID
        $rMedicao = $this->repository->create($medicao);
        $this->updateProcess($medicao, $rMedicao->medicao_id);
        return true;
    }


    public function edit()
    {
        //
    }

    public function update($medicao_id, $data)
    {
        $this->repository->update($medicao_id, $data);
        return true;
    }

    public function show($medicao_id)
    {
        $medicao = $this->getMedicao($medicao_id);
        $this->medicaoFiscais($medicao, $medicao_id);
        return $this->medicaoFiscaisModel->where("medicao_id", $medicao_id)->orderBy('fiscal_name', 'asc')->get();
    }

    public function getMedicao($medicao_id)
    {
        return $this->repository->find($medicao_id);
    }

    public function getFiscal($fiscal_id)
    {
        return $this->users->find($fiscal_id);
    }

    public function fiscalDetails($medicao, $medicao_id, $fiscal_id)
    {
        return $this->repository->medicaoFiscal($medicao, $medicao_id, $fiscal_id);
    }

    public function medirVistoria($medicao_id, $vistoria_id, $tipo_id)
    {
        return $this->repository->medirVistoria($medicao_id, $vistoria_id, $tipo_id); //PENDETE CRIAR
    }

    public function vistoriasMedidas($medicao, $medicao_id, $fiscal_id, $tipo_id = [])
    {
        if (is_array($tipo_id)) {
            $tipos = "".$tipo_id[0].",".$tipo_id[1]."";
        } else {
            $tipos = $tipo_id;
        }

        return DB::select("SELECT 
            vistorias.id as vistoria_id,
            vistorias.codigo,
            predios.codigo as cod_predio,
            tipos.vistoria_tipo_id as tipo_id,
            date_format(vistorias.dt_vistoria, '%d/%m/%Y') AS date_vistoria,
            envios.created_at AS date_envio,
            vistorias.status
        FROM vistorias
        INNER JOIN pis ON pis.id = vistorias.pi_id
        INNER JOIN predios ON predios.id = pis.id_predio
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
        LEFT JOIN lista_vistoria_envios AS envios ON envios.vistoria_id = vistorias.id
        INNER JOIN medicao ON medicao.medicao_id = vistorias.medicao_id
        WHERE 
            tipos.vistoria_tipo_id IN ($tipos)
        and
            medicao.medicao_id = '$medicao_id'
        AND 
            vistorias.cod_fiscal_pi = '$fiscal_id'

        UNION
        
        SELECT 
        vistorias_multiplas.id as vistoria_id,
        CASE 
            when vistorias_multiplas.cod_pi IS NULL AND predios.codigo IS NOT NULL then predios.codigo 
            ELSE vistorias_multiplas.cod_pi
        END AS codigo,
            predios.codigo as cod_predio,
            tipos.vistoria_tipo_id as tipo_id,
            date_format(vistorias_multiplas.dt_vistoria, '%d/%m/%Y') AS date_vistoria,
            envios.created_at AS date_envio,
            vistorias_multiplas.status
        FROM vistorias_multiplas
        INNER JOIN predios ON predios.id = vistorias_multiplas.predio_id
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
        LEFT JOIN lista_vistoria_envios_multiplos AS envios ON envios.vistoria_id = vistorias_multiplas.id
        INNER JOIN medicao ON medicao.medicao_id = vistorias_multiplas.medicao_id
        WHERE 
            tipos.vistoria_tipo_id IN ($tipos)
        and
            medicao.medicao_id = '$medicao_id'
        AND 
            vistorias_multiplas.fiscal_user_id = '$fiscal_id'");
    }

    public function vistoriasDisponiveis($medicao, $fiscal_id, $tipo_id = [])
    {
        if (is_array($tipo_id)) {
            $tipos = "".$tipo_id[0].",".$tipo_id[1]."";
        } else {
            $tipos = $tipo_id;
        }

        return DB::select("SELECT 
        vistorias.id as vistoria_id,
        vistorias.codigo,
        tipos.vistoria_tipo_id AS tipo_id,
        predios.name,
        predios.codigo as cod_predio,
        date_format(vistorias.dt_vistoria, '%d/%m/%Y') AS date_vistoria,
        envios.created_at AS date_envio,
        vistorias.status
        FROM vistorias
        INNER JOIN pis ON pis.id = vistorias.pi_id
        INNER JOIN predios ON predios.id = pis.id_predio
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
        left JOIN lista_vistoria_envios AS envios ON envios.vistoria_id = vistorias.id
        left JOIN medicao ON medicao.medicao_id = vistorias.medicao_id
        WHERE 
        tipos.vistoria_tipo_id IN ($tipos)
        AND vistorias.dt_vistoria <= '$medicao[dt_fim]'
        AND vistorias.status IN ('Aprovado','Enviado')  
        and
        (vistorias.medicao_id = '0' OR vistorias.medicao_id IS null)
        AND 
        vistorias.cod_fiscal_pi = '$fiscal_id'
        
        
        UNION
        
        SELECT 
        vistorias_multiplas.id as vistoria_id,
        CASE 
            when vistorias_multiplas.cod_pi IS NULL AND predios.codigo IS NOT NULL then predios.codigo 
            ELSE vistorias_multiplas.cod_pi
        END AS codigo,
        tipos.vistoria_tipo_id AS tipo_id,
        predios.codigo as cod_predio,
        predios.name,
        date_format(vistorias_multiplas.dt_vistoria, '%d/%m/%Y') AS date_vistoria,
        envios.created_at AS date_envio,
        vistorias_multiplas.status
        FROM vistorias_multiplas
        INNER JOIN predios ON predios.id = vistorias_multiplas.predio_id
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
        left JOIN lista_vistoria_envios_multiplos AS envios ON envios.vistoria_id = vistorias_multiplas.id
        left JOIN medicao ON medicao.medicao_id = vistorias_multiplas.medicao_id
        WHERE 
        tipos.vistoria_tipo_id IN ($tipos)
        AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]'
        AND vistorias_multiplas.status IN ('Aprovado','Enviado')  
        and
        (vistorias_multiplas.medicao_id = '0' OR vistorias_multiplas.medicao_id IS NULL)
        AND 
        vistorias_multiplas.fiscal_user_id = '$fiscal_id'
        ");
    }

    public function vistoriasPendentes($medicao, $fiscal_id, $tipo_id = [])
    {
        if (is_array($tipo_id)) {
            $tipos = "".$tipo_id[0].",".$tipo_id[1]."";
        } else {
            $tipos = $tipo_id;
        }

        return DB::select("SELECT 
        vistorias.id as vistoria_id,
        vistorias.codigo,
        tipos.vistoria_tipo_id AS tipo_id,
        predios.name,
        predios.codigo as cod_predio,
        date_format(vistorias.dt_vistoria, '%d/%m/%Y') AS date_vistoria,
        envios.created_at AS date_envio,
        vistorias.status
        FROM vistorias
        INNER JOIN pis ON pis.id = vistorias.pi_id
        INNER JOIN predios ON predios.id = pis.id_predio
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
        left JOIN lista_vistoria_envios AS envios ON envios.vistoria_id = vistorias.id
        left JOIN medicao ON medicao.medicao_id = vistorias.medicao_id
        WHERE 
        tipos.vistoria_tipo_id IN ($tipos)
        AND vistorias.dt_vistoria <= '$medicao[dt_fim]'
        AND vistorias.status NOT IN ('Aprovado','Enviado')  
        and
        (vistorias.medicao_id = '0' OR vistorias.medicao_id IS null)
        AND 
        vistorias.cod_fiscal_pi = '$fiscal_id'
        
        
        UNION
        
        SELECT 
        vistorias_multiplas.id as vistoria_id,
        CASE 
            when vistorias_multiplas.cod_pi IS NULL AND predios.codigo IS NOT NULL then predios.codigo 
            ELSE vistorias_multiplas.cod_pi
        END AS codigo,
        tipos.vistoria_tipo_id AS tipo_id,
        predios.codigo as cod_predio,
        predios.name,
        date_format(vistorias_multiplas.dt_vistoria, '%d/%m/%Y') AS date_vistoria,
        envios.created_at AS date_envio,
        vistorias_multiplas.status
        FROM vistorias_multiplas
        INNER JOIN predios ON predios.id = vistorias_multiplas.predio_id
        INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
        left JOIN lista_vistoria_envios_multiplos AS envios ON envios.vistoria_id = vistorias_multiplas.id
        left JOIN medicao ON medicao.medicao_id = vistorias_multiplas.medicao_id
        WHERE 
        tipos.vistoria_tipo_id IN ($tipos)
        AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]'
        AND vistorias_multiplas.status NOT IN ('Aprovado','Enviado')  
        and
        (vistorias_multiplas.medicao_id = '0' OR vistorias_multiplas.medicao_id IS NULL)
        AND 
        vistorias_multiplas.fiscal_user_id = '$fiscal_id'
        ");
    }

    /**
     * Função que retorna todas as despesas atreladas a medição
     */
    public function listDespesas($medicao_id, $fiscal_id)
    {
        return $this->despesasModel->where('medicao_id', $medicao_id)->where('fiscal_id', $fiscal_id)->get();
    }
    
    /**
     * Função Responsável pela listagem de vistorias disponiveis para serem medidas.
     * @param $medicao_id, $fiscal_id.
     * @return json
     **/
    public function vistoriasDespesasList($dt_recibo, $medicao_id, $fiscal_id, $despesa_id = null)
    {

        if (!empty($despesa_id)) {
            return DB::select("SELECT 
                    despesas.n_recibo as n_recibo,
                    despesas.dt_recibo as data_recibo,
                    despesas.amount as valor,
                    despesas.type as tipo_despesa,
                    vistorias.id as id,
                    tipo.vistoria_tipo_id AS tipo_id,
                    vistorias.codigo AS codigo,
                    tipo.name AS tipo_vistoria,
                    predios.name as escola,
                    date_format(vistorias.dt_vistoria, '%d/%m/%Y') as dt_vistoria
                FROM despesas 
                    INNER JOIN despesas_vistorias AS pivot ON pivot.despesa_id = despesas.id
                    INNER JOIN vistorias ON (vistorias.id = pivot.vistoria_id AND vistorias.tipo_id = pivot.type_id)
                    INNER join pis on pis.id = vistorias.pi_id
                    INNER JOIN predios on predios.id = pis.id_predio
                    INNER JOIN vistoria_tipos AS tipo ON tipo.vistoria_tipo_id = vistorias.tipo_id
                WHERE despesas.id = '{$despesa_id}'

                UNION

                SELECT 
                    despesas.n_recibo as n_recibo,
                    despesas.dt_recibo as data_recibo,
                    despesas.amount as valor,
                    despesas.type as tipo_despesa,
                    multiplas.id as id,
                    tipo.vistoria_tipo_id AS tipo_id,
                    CASE 
                    when multiplas.cod_pi IS NULL THEN predios.codigo
                    ELSE multiplas.cod_pi
                    END AS codigo,
                    tipo.name AS tipo_vistoria,
                    predios.name as escola,
                    date_format(multiplas.dt_vistoria, '%d/%m/%Y') as dt_vistoria
                FROM despesas 
                    INNER JOIN despesas_vistorias AS pivot ON pivot.despesa_id = despesas.id
                    INNER JOIN vistorias_multiplas AS multiplas ON (multiplas.id = pivot.vistoria_id AND multiplas.tipo_id = pivot.type_id)
                    LEFT join pis on pis.id = multiplas.pi_id
                    LEFT JOIN predios on predios.id = multiplas.predio_id
                    INNER JOIN vistoria_tipos AS tipo ON tipo.vistoria_tipo_id = multiplas.tipo_id
                WHERE despesas.id = '{$despesa_id}'
            ");
        } else {
            return DB::select("SELECT 
                    vistorias.id as id,
                    tipos.vistoria_tipo_id as tipo_id,
                    vistorias.codigo AS codigo,
                    tipos.name AS tipo_vistoria,
                    predios.name as escola,
                    date_format(vistorias.dt_vistoria, '%d/%m/%Y') as dt_vistoria
                FROM vistorias 
                    inner join pis on pis.id = vistorias.pi_id
                    INNER JOIN predios on predios.id = pis.id_predio
                    INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
                WHERE 
                vistorias.medicao_id = $medicao_id 
                AND 
                vistorias.cod_fiscal_pi = '$fiscal_id'
                AND
                vistorias.dt_vistoria = '$dt_recibo'
                    
                UNION

                SELECT 
                    vistorias_multiplas.id as id,
                    tipos.vistoria_tipo_id as tipo_id,
                    CASE 
                        when vistorias_multiplas.cod_pi IS NULL THEN predios.codigo
                        ELSE vistorias_multiplas.cod_pi
                    END AS codigo,
                    tipos.name AS tipo_vistoria,
                    predios.name as escola,
                        date_format(vistorias_multiplas.dt_vistoria, '%d/%m/%Y') as dt_vistoria
                FROM vistorias_multiplas
                    LEFT join pis on pis.id = vistorias_multiplas.pi_id
                    LEFT JOIN predios on predios.id = vistorias_multiplas.predio_id
                    INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
                WHERE 
                vistorias_multiplas.medicao_id = $medicao_id 
                AND 
                vistorias_multiplas.fiscal_user_id = '$fiscal_id'
                AND
                vistorias_multiplas.dt_vistoria = '$dt_recibo'
            ");
        }
    }

    /**
     * Função Responsável pela listagem de vistorias disponiveis para serem medidas.
     * @param $medicao_id, $fiscal_id.
     * @return json
     **/
    public function vistoriasDespesasDisponivelList($dt_recibo, $medicao_id, $fiscal_id, $despesa_id = null)
    {
            return DB::select("SELECT 
                    vistorias.id as id,
                    vistorias.codigo as codigo,
                    tipos.name AS tipo_vistoria,
                    tipos.vistoria_tipo_id AS tipo_id,
                    date_format(vistorias.dt_vistoria, '%d/%m/%Y') as dt_vistoria,
                    predios.name as escola 
                FROM vistorias 
                    INNER join pis on pis.id = vistorias.pi_id
                    INNER JOIN predios on predios.id = pis.id_predio
                    INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = vistorias.tipo_id
                WHERE 
                vistorias.medicao_id = $medicao_id
                AND 
                vistorias.cod_fiscal_pi = '$fiscal_id'
                AND
                vistorias.dt_vistoria = '$dt_recibo'
                AND
                vistorias.id NOT IN (SELECT vistoria_id FROM despesas_vistorias WHERE type_id IN ('1','2','3'))
                
                
                UNION
                
                SELECT 
                    multiplas.id as id,
                    CASE 
                        when multiplas.cod_pi IS NULL THEN predios.codigo
                        ELSE multiplas.cod_pi
                    END AS codigo,
                    tipos.name AS tipo_vistoria,
                    tipos.vistoria_tipo_id AS tipo_id,
                    date_format(multiplas.dt_vistoria, '%d/%m/%Y') as dt_vistoria,
                    predios.name as escola 
                FROM vistorias_multiplas AS multiplas
                    LEFT join pis on pis.id = multiplas.pi_id
                    LEFT JOIN predios on predios.id = multiplas.predio_id
                    INNER JOIN vistoria_tipos AS tipos ON tipos.vistoria_tipo_id = multiplas.tipo_id
                WHERE 
                multiplas.medicao_id = '$medicao_id'
                AND 
                multiplas.fiscal_user_id = '$fiscal_id'
                AND
                multiplas.dt_vistoria = '$dt_recibo'
                AND
                multiplas.id NOT IN (SELECT vistoria_id FROM despesas_vistorias WHERE type_id NOT IN ('1','2','3'))
            ");
    }

    /**
     * Função Responsável pela criação de uma despesa e vinculo com as vistorias.
     * 
     * @param $data
     * @return boolean
     */
    public function createDespesa($data)
    {
        DB::beginTransaction();

        try {
            $despesa = $this->despesasModel->create([
                'dt_recibo' => $data['dt_recibo'],
                'amount' => $data['amount'],
                'n_recibo' => $data['n_recibo'],
                'medicao_id'=> $data['medicao_id'],
                'fiscal_id' => $data['fiscal_id'],
                'type' => $data['type'],
                'created_by' => Auth::id(),
                'created_at' => now()
            ]);

            foreach ($data['vistorias_id'] as $index => $vistoria_id) {
                if (!empty($vistoria_id)) {
                    $despesa_vistorias = $this->despesasVistoriasModel->create([
                        'despesa_id' => $despesa->id,
                        'vistoria_id' => $vistoria_id,
                        'type_id' => $data['tipos_id'][$index]
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    /**
     * Função Responsável pela criação de uma despesa e vinculo com as vistorias.
     * 
     * @param $data
     * @return boolean
     */
    public function updateDespesa($data)
    {
        DB::beginTransaction();
    
        try {
            $this->despesasModel->find($data['despesa_id'])->update([
                'n_recibo' => $data['n_recibo_update'],
                'dt_recibo' => $data['dt_recibo_update'],
                'amount' => $data['amount_update'],
                'fiscal_id' => $data['fiscal_id'],
                'type' => $data['type_update'],
            ]);

            $despesa =  $this->despesasModel->find($data['despesa_id']);
            foreach ($data['vistorias_despesa_update_check'] as $key => $vistoria_id) {
                if (!empty($vistoria_id)) {
                    $despesa_vistorias = $this->despesasVistoriasModel->updateOrCreate(
                        [
                        'despesa_id' => $despesa->id,
                        'vistoria_id' => $vistoria_id
                        ],
                        [
                            'despesa_id' => $despesa->id,
                            'vistoria_id' => $vistoria_id,
                            'type_id' =>  $data['vistoria_tipo_update_'.$vistoria_id]
                        ]
                    );
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
    
    /**
     * Função Responsável pela atualização das vistorias, incrementando o ID da medição. (medicao_id)
     * Realiza uma busca em todas as vistorias exceto (Segurança e Gestão Social), e atualiza mediante o raio de inicio e fim.
     * @author Felipe Silva
     * @param $medicao (array),
     */
    private function updateProcess($medicao, $medicao_id)
    {
        try {
            $this->updateVistorias($medicao, $medicao_id);
            $this->medicaoFiscais($medicao, $medicao_id); //Criar o registro da medicao de cada fiscal.
            return true;
        } catch (Excepetion $e) {
            dd($e->getMessage()); //debug console.
        }
    }

    private function updateVistorias($medicao, $medicao_id)
    {
        $qtdVistorias = 0;
        //Atualiza
        //
        $vistorias = $this->vistoriaModel
        ->whereBetween('dt_vistoria', [$medicao['dt_inicio'], $medicao['dt_fim']])
        ->whereIn('status', ['Aprovado', 'Enviado'])
        ->where("medicao_id", "=", '0')
        ->update(['medicao_id' => $medicao_id]);
    
        $vistoriasMultiplas = $this->vistoriaMultiplasModel
        ->whereBetween('dt_vistoria', [$medicao['dt_inicio'], $medicao['dt_fim']])
        ->whereIn('status', ['Aprovado', 'Enviado'])
        ->whereNotIn('tipo_id', ['7','8']) //Exceto Segurança do Trabalho e Gestão social
        ->where("medicao_id", "=", '0')
        ->update(['medicao_id' => $medicao_id]);

        //Consulta
        $vistorias = $this->vistoriaModel->where('medicao_id', $medicao_id)->get();
        $vistoriasMultiplas = $this->vistoriaMultiplasModel->where('medicao_id', $medicao_id)->get();

        $qtdVistorias = (count($vistorias) + count($vistoriasMultiplas));

        //Atualiza Medição
        $this->update($medicao_id, ['qtd_vinculadas' => $qtdVistorias]);
    }

    /**
     * Função responsável por armazenar a sintetização das informações atreladas a medição, vistorias e fiscais..
     */
    private function medicaoFiscais($medicao, $medicao_id)
    {
        
        $medicao_fiscais = DB::select("SELECT 
        fiscal_id,
        Fiscal AS fiscal_name,
        sum(TotalMedido) AS total_medido,
        sum(Valor) AS valor_medido,
        sum(TotalDisponivel) AS total_disponivel,
        SUM(valorDisponivel) AS valor_disponivel,
        sum(TotalPendentes) AS total_pendente,
        SUM(valorPendentes) AS valor_pendente,
        despesas as total_despesas
              FROM (
              SELECT 
              users.id AS fiscal_id,
              users.name AS Fiscal,
              SUM(
                CASE
                WHEN medicao.medicao_id = '$medicao_id' and vistorias.medicao_id = '$medicao_id' then 1 
                  ELSE 0
              END) AS TotalMedido,
              SUM(
                  CASE 
                      WHEN medicao.medicao_id = '$medicao_id' and vistorias.medicao_id = '$medicao_id' THEN  vistoria_tipos.price
                      ELSE 0
                  end) AS Valor,
                  SUM(
              CASE
                  when (vistorias.medicao_id IS NULL OR vistorias.medicao_id = 0)  AND vistorias.dt_vistoria <= '$medicao[dt_fim]' AND vistorias.status IN ('Aprovado','Enviado')   then 1 
                  ELSE 0
              END) AS TotalDisponivel,
              SUM(
                  CASE 
                      WHEN (vistorias.medicao_id IS NULL OR vistorias.medicao_id = 0) AND vistorias.dt_vistoria <= '$medicao[dt_fim]' AND vistorias.status IN ('Aprovado','Enviado')  THEN  vistoria_tipos.price
                      ELSE 0
                  end) AS valorDisponivel,
              SUM(CASE
                  when (vistorias.medicao_id IS NULL OR vistorias.medicao_id  = 0) AND vistorias.dt_vistoria <= '$medicao[dt_fim]'  AND vistorias.status NOT IN ('Aprovado','Enviado')   then 1 
                  ELSE 0
              END) AS TotalPendentes,
              SUM(
                  CASE 
                      WHEN (vistorias.medicao_id IS NULL OR vistorias.medicao_id = 0) AND vistorias.dt_vistoria <= '$medicao[dt_fim]' AND vistorias.status NOT IN ('cadastro')   THEN  vistoria_tipos.price
                      ELSE 0
                  end) AS valorPendentes,
                    CASE 
                        WHEN despesas.medicao_id ='$medicao_id' and despesas.fiscal_id = vistorias.cod_fiscal_pi then despesas.amount 
                        ELSE 0
                    END as despesas
              FROM vistorias 
              INNER JOIN vistoria_tipos ON vistoria_tipos.vistoria_tipo_id = vistorias.tipo_id
              INNER JOIN users ON users.id = vistorias.cod_fiscal_pi
              LEFT JOIN medicao ON medicao.medicao_id = vistorias.medicao_id
              LEFT JOIN despesas  ON despesas.fiscal_id = vistorias.cod_fiscal_pi
              GROUP by Fiscal
              
              UNION 
              
              SELECT 
              users.id AS fiscal_id,
              users.name AS Fiscal,
              SUM(
                CASE
                    WHEN medicao.medicao_id = '$medicao_id' and vistorias_multiplas.medicao_id = '$medicao_id' then 1 
                  ELSE 0
              END) AS TotalMedido,
              SUM(
                  CASE 
                      WHEN medicao.medicao_id ='$medicao_id'  and vistorias_multiplas.medicao_id ='$medicao_id' THEN  vistoria_tipos.price
                      ELSE 0
                  end) AS Valor,
                  SUM(
              CASE
                  when (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id  = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]'  AND vistorias_multiplas.status IN ('Aprovado','Enviado')   then 1 
                  ELSE 0
              END) AS TotalDisponivel,
              SUM(
                  CASE 
                      WHEN (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]' AND vistorias_multiplas.status IN ('Aprovado','Enviado')   THEN  vistoria_tipos.price
                      ELSE 0
                  end) AS valorDisponivel,
               SUM(CASE
                  when (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id  = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]'  AND vistorias_multiplas.status NOT IN ('cadastro')   then 1 
                  ELSE 0
              END) AS TotalPendentes,
              SUM(
                  CASE 
                      WHEN (vistorias_multiplas.medicao_id IS NULL OR vistorias_multiplas.medicao_id = 0) AND vistorias_multiplas.dt_vistoria <= '$medicao[dt_fim]' AND vistorias_multiplas.status NOT IN ('cadastro')   THEN  vistoria_tipos.price
                      ELSE 0
                  end) AS valorPendentes,
                    CASE 
                        WHEN despesas.medicao_id ='$medicao_id' and despesas.fiscal_id = vistorias_multiplas.fiscal_user_id then despesas.amount 
                        ELSE 0
                    END as despesas
              FROM vistorias_multiplas
              INNER JOIN users ON users.id = vistorias_multiplas.fiscal_user_id
              INNER JOIN vistoria_tipos ON vistoria_tipos.vistoria_tipo_id = vistorias_multiplas.tipo_id
              LEFT JOIN medicao ON medicao.medicao_id = vistorias_multiplas.medicao_id
              LEFT JOIN despesas  ON despesas.fiscal_id = vistorias_multiplas.fiscal_user_id
              GROUP by Fiscal
              ) t
              GROUP BY fiscal_name");

        $this->createMedicaoFiscais($medicao_fiscais, $medicao_id);
        return true;
    }

    /**
     * Função responsavel pela criação do registro na tabela medicao_fiscal
     */
    private function createMedicaoFiscais($data, $medicao_id)
    {
        try {
            foreach ($data as $key => $fiscal) {
                $validateStatus = $this->medicaoFiscaisModel->where('medicao_id', $medicao_id)->where('fiscal_id', $fiscal->fiscal_id)->first();
            
                $data = [
                    'medicao_id' => $medicao_id,
                    'fiscal_id' => $fiscal->fiscal_id,
                    'fiscal_name' => $fiscal->fiscal_name,
                    'total_medido' => $fiscal->total_medido,
                    'valor_medido' => $fiscal->valor_medido,
                    'total_disponivel' => $fiscal->total_disponivel,
                    'valor_disponivel' => $fiscal->valor_disponivel,
                    'total_pendente' => $fiscal->total_pendente,
                    'valor_pendente' => $fiscal->valor_pendente,
                    'despesas' => $fiscal->total_despesas,
                    'status' => $validateStatus->status ?? 'I'
                ];
                $this->medicaoFiscaisModel->updateOrCreate(['medicao_id' => $medicao_id,'fiscal_id' => $fiscal->fiscal_id], $data);
            }
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());//debug console.
        }
    }

    //RELATORIOS DE FISCAIS

    /**
     * Função responsável por retornar as tabelas que serão apresentadas nos relatórios.
     */
    public function returnTableInReport($medicao_id, $fiscal_id, $despesa = false)
    {
        if ($despesa) {
            $returns = $this->repository->relatoryDespesas($medicao_id, $fiscal_id);
            foreach ($returns as $type => $values) {
                $return[] = $this->colunsGetteTypes($values, $type);
            }
   
            return $return;
        }
        $fields = $this->repository->relatoryMedicoes($medicao_id, $fiscal_id);
        return $this->colunsGetteTypes($fields);
    }

    public function companyFiscal($fiscal_id)
    {
        return $this->companyModel->where('user_id', $fiscal_id)->first();
    }

    private function colunsGetteTypes($data, $type = 'default')
    {
        switch ($type) {
            case 'despesas':
                $return[$type]['table']['theads'] = [
                    'Tipo de Despesa',
                    'Data',
                    'Valor',
                    'Nº Recibo'
                ];
                $return[$type]['table']['tbody'] = $data;
                break;
            case 'realizadas':
                $return[$type]['table']['theads'] = [
                    'Prédio',
                    'Código PI',
                    'Tipo da Vistoria',
                    'Data da Vistoria'
                ];
                $return[$type]['table']['tbody'] = $data;
                break;
            case 'default':
                $return['table']['theads'] = [
                    'Prédio',
                    'Código PI',
                    'Data da Vistoria'
                ];
                $return['table']['tbody'] = $data;
                break;
        }
        return $return;
    }

    public function saveFile($data, $file)
    {
        $fiscal = $this->getFiscal($data['fiscal_id']);
        $path = $this->repository->saveFile($data, $file, $fiscal);
        $filename = $file->getClientOriginalName();
        Storage::disk('public')->put($path, $file);
        $filename = str_replace(' ', '', $data['name_archive']);
        $file->move(public_path("{$path}"), $filename.'.pdf');
        //$return = Storage::putFileAs("public/{$path}", $file, $data['name_archive'].'.pdf');
        return true;
    }

    public function listAnexos($medicao_id, $fiscal_id)
    {
        return $this->repository->getAnexos($medicao_id, $fiscal_id);
    }

    public function atualizarStatusMedicaoFiscais($medicao_fiscal_id, $status = "F")
    {
        return $this->medicaoFiscaisModel->find($medicao_fiscal_id["medicao_fiscal_id"])->update(['status' => "F"]);
    }
}
