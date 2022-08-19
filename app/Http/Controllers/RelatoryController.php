<?php

namespace App\Http\Controllers;

use App\Models\Vistoria;
use App\Models\Users;
use Illuminate\Http\Request as GenericRequest;
use Illuminate\Support\Facades\DB;
use League\Csv\Writer;

class RelatoryController extends Controller
{
    private $model;

    public function __construct(Vistoria $model)
    {
        $this->model = $model;
    }
    /**
    * View responsável pelo filtro de pesquisa para gerar o relatório.
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return view 
    **/
    public function index()
    {
        $fiscais = Users::where('grupo', 'Fiscal')->get();
        return view(
            'relatory.supervisor.index',
            compact(
                'fiscais'
            )
        );
    }
    /**
    * Função nativa, responsável pelo desencadeamento de functions responsável pela montagem do relatório com base no filtro.
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    public function Relatory(GenericRequest $request)
    {

        
        $query = $this->RelatoryQueryConsulting($request->except('_token'));
    }
    /**
    * Responsável por realizar a comunicação com banco de dados, para tabulação de resultados, pelo tipo escolhido.
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RelatoryQueryConsulting($data)
    {
        
        if ($data['tipo_relatorio'] == 'rt_custo') {
            $consulta = $this->RelatoryCost($data);
        } else {
            $consulta = $this->RoadmapReport($data);
        }
        $this->RelatoryCsv($consulta, $data);
    }
    /**
    * Responsável por realizar a montagem do relatório com base na consulta realizada
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RelatoryCsv($query, $data)
    {
        $fiscal = $this->Fiscal($data['fiscal']);
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        $this->RelatoryHeader($data, $fiscal->name, $csv);
        $this->RelatoryColumns($data, $csv, $fiscal->name);
        $result = $this->object_to_array($query);
        $this->InsertRow($csv, $result, $data);
        $csv->output($this->RelatoryName($data, $fiscal->name));
        return response($csv, 200, ['Content-Type' => 'application/xml']);
    }
    private function object_to_array($object)
    {
        //torna o objeto em array;
        $result = array_map(function ($value) {
            return (array)$value;
        }, $object);
        return $result;
    }
    /**
    * Responsável por nomear o arquivo
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RelatoryName($data, $fName)
    {
        return "relatorio_{$data['tipo_relatorio']}_Fiscal_{$fName}.xls";
    }
    /**
    * Responsável por inserir as linhas do relatório
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function InsertRow($csv, $result, $data)
    {
        $soma = 0;
        $row = [];
        foreach ($result as $info) {
            if ($data['tipo_relatorio'] == 'rt_roteiro') {
                $row[] = [
                    'nome_escola' =>  $info['nome_escola'],
                    'data_vistoria' => $info['data_da_vistoria'],
                    'quantidade'    => '', /*quantidade de pedágios inserido manualmente pela Samantha, alinhado 09/02/2022*/
                    'valor_total' => '' /*valor total dos pedágios inserido manualmente pela Samantha, alinhado 09/02/2022*/
                ];
            } else  {
                $soma += $info['fvalor_total'];
                $row[] = [
                    'tipo_vistoria' => $info['tipo_vistoria'],
                    'quantidade' => $info['quantidade'],
                    'valor_total' => $info['fvalor_total']
                ];
            }
        }

        if(count($row) == 0)
            $csv->insertOne("SEM REGISTROS PARA GERAR UM RELATÓRIO");

        foreach ($row as $line) {
            $csv->insertOne($line);
        }
        if ($data['tipo_relatorio'] != 'rt_roteiro') {
            $csv->insertOne(['Valor Total:', (float)$soma]);
        }
        return $csv;
    }
    /**
    * Responsável por montar os colunas dos relatórios
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RelatoryColumns($data, $csv, $Fiscal)
    {
        if ($data['tipo_relatorio'] == 'rt_roteiro') {
            return $csv->insertOne(['Escola','Data','Quantidade de Pedagios', 'Valor cada Pedagio']);
        }
        return $csv->insertOne(['Tipo de vistoria','Quantidade','Valor a ser Pago']); //Incluir valor da Receber antes da coluna quantidade
    }
    /**
    * Responsável por montar o cabeçalho dos relatórios
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RelatoryHeader($data, $fNome, $csv)
    {
        $csv->insertOne('1 - Dados do Usúario');

        $csv->insertOne(['Funcionario(a):', $fNome, 'Cargo/Função:', '']);

        $csv->insertOne('___'); //linha em branco

        $csv->insertOne(["Competência: {$data['competencia']}"]); // ajustar tamanho das colunas
        
        if ($data['tipo_relatorio'] == 'rt_roteiro')
            $csv->insertOne('2 - Roteiro de Viagens');

        $csv->insertOne('___'); //linha em branco

        return $csv;
    }
    /**
    * Query para tabulação de resultados de acompanhamento "ROTEIRO"
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RoadmapReport($data)
    {
        return DB::select("SELECT 
            predios.name AS nome_escola,
            date_format(vistoria.dt_vistoria,'%d/%m/%Y') AS data_da_vistoria,
            date_format(envios.created_at,'%d/%m/%Y') AS envio_fde,
            lista.mes AS mes_referencia,
            fiscal_pi.name AS fiscal
        FROM lista_envios AS lista
            INNER JOIN lista_vistoria_envios AS envios ON envios.lista_id = lista.id  
            INNER JOIN vistorias AS vistoria ON vistoria.id = envios.vistoria_id
            INNER JOIN pis ON pis.id = vistoria.pi_id
            INNER JOIN predios ON predios.id = pis.id
            INNER JOIN vistoria_tipos ON vistoria_tipos.vistoria_tipo_id = vistoria.tipo_id 
            INNER JOIN users AS fiscal_pi ON fiscal_pi.id = pis.id_user
        WHERE fiscal_pi.id = '{$data['fiscal']}'
        AND
            lista.mes = '{$data['competencia']}'
        UNION	
    
        SELECT 
            predios_mult.name AS nome_escola,
            date_format(vistoria_mult.dt_vistoria,'%d/%m/%Y') AS data_da_vistoria,
            date_format(envios_mult.created_at,'%d/%m/%Y') AS envio_fde,
            lista_mult.mes AS mes_referencia,
            fiscal_mult.name AS fiscal
        FROM lista_envios_multiplos AS lista_mult
            INNER JOIN lista_vistoria_envios_multiplos AS envios_mult ON envios_mult.lista_id = lista_mult.id  
            INNER JOIN vistorias_multiplas AS vistoria_mult ON vistoria_mult.id = envios_mult.vistoria_id
            LEFT JOIN pis  as pis_mult ON pis_mult.id = vistoria_mult.pi_id
            LEFT JOIN predios  as predios_mult ON predios_mult.id = vistoria_mult.predio_id
            INNER JOIN vistoria_tipos AS tipos_mult ON tipos_mult.vistoria_tipo_id = vistoria_mult.tipo_id 
            INNER JOIN users AS fiscal_mult ON fiscal_mult.id = vistoria_mult.fiscal_user_id
        WHERE fiscal_mult.id = '{$data['fiscal']}'
        AND
            lista_mult.mes = '{$data['competencia']}'
        ORDER BY data_da_vistoria");
    }
    /**
    * Query para tabulação de resultados de acompanhamento "CUSTO"
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function RelatoryCost($data)
    {
        return DB::select("SELECT 
        tipo_vistoria,
        COUNT(*) AS quantidade,
        SUM(pagar_fiscal) AS fvalor_total		
        FROM
        (
            SELECT 
            fiscal_pi.name AS fiscal,
            predios.name AS nome_escola,
            vistoria.codigo AS codigo,
            date_format(vistoria.dt_vistoria,'%d/%m/%Y') AS data_da_vistoria,
            vistoria_tipos.price AS pagar_fiscal,
            vistoria_tipos.name AS tipo_vistoria,
            case 
                when vistoria.status = 'Enviado' then 'Pago pela FDE'
                ELSE 'Não paga'
            END AS STATUS
            FROM lista_envios AS lista
            INNER JOIN lista_vistoria_envios AS envios ON envios.lista_id = lista.id  
            INNER JOIN vistorias AS vistoria ON vistoria.id = envios.vistoria_id
            INNER JOIN pis ON pis.id = vistoria.pi_id
            INNER JOIN predios ON predios.id = pis.id
            INNER JOIN vistoria_tipos ON vistoria_tipos.vistoria_tipo_id = vistoria.tipo_id 
            INNER JOIN users AS fiscal_pi ON fiscal_pi.id = pis.id_user
            WHERE fiscal_pi.id = '{$data['fiscal']}'
            AND
                lista.mes = '{$data['competencia']}'
            
            UNION	
            
            SELECT 
                fiscal_mult.name AS fiscal,
                predios_mult.name AS nome_escola,
                CASE 
                WHEN pis_mult.codigo IS NULL then predios_mult.codigo
                ELSE pis_mult.codigo
            END AS codigo,
            date_format(vistoria_mult.dt_vistoria,'%d/%m/%Y') AS data_da_vistoria,
            case 
                when tipos_mult.vistoria_tipo_id = 7 AND vistoria_mult.check_avanco = 'inicial' then 120
                when tipos_mult.vistoria_tipo_id = 7 AND vistoria_mult.check_avanco = 'intermediario' then 120
                when tipos_mult.vistoria_tipo_id = 7 AND vistoria_mult.check_avanco = 'final' then 120
                ELSE tipos_mult.price
            END AS pagar_fiscal,
            tipos_mult.name AS tipo_vistoria,
            case 
                when vistoria_mult.status = 'Enviado' then 'Pago pela FDE'
            ELSE 'Não paga'
            END AS status
            FROM lista_envios_multiplos AS lista_mult
            INNER JOIN lista_vistoria_envios_multiplos AS envios_mult ON envios_mult.lista_id = lista_mult.id  
            INNER JOIN vistorias_multiplas AS vistoria_mult ON vistoria_mult.id = envios_mult.vistoria_id
            INNER JOIN vistoria_tipos AS tipos_mult ON tipos_mult.vistoria_tipo_id = vistoria_mult.tipo_id 
            LEFT JOIN pis  as pis_mult ON pis_mult.id = vistoria_mult.pi_id
            LEFT JOIN predios  as predios_mult ON predios_mult.id = vistoria_mult.predio_id
            INNER JOIN users AS fiscal_mult ON fiscal_mult.id = vistoria_mult.fiscal_user_id
            WHERE fiscal_mult.id = '{$data['fiscal']}'
            AND
                lista_mult.mes = '{$data['competencia']}'
        ) AS rt_custo
            WHERE rt_custo.status = 'Pago pela FDE' 
            GROUP BY rt_custo.tipo_vistoria
            ORDER BY data_da_vistoria");
    }
    /**
    * Responsável por montar o cabeçalho dos relatórios
    * @author Felipe Silva <felipe.silva@cespfde.com.br>
    * @return void 
    **/
    private function Fiscal($Fiscal_id)
    {
        return Users::select('name')->find($Fiscal_id);
    }
}