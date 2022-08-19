<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vistoria extends Model
{
    use HasFactory;
    protected $table = 'vistorias';
    protected $dates = ['deleted_at','dt_vistoria'];
    public $timestamps = true;
    protected $primaryKey = 'id';

//definir fillabel com base na tabela de vistorias
//Definir softdelet()
    protected $fillable = [
        'codigo',
        'pi_id',
        'item_id',
        'num_vistoria',
        'dt_vistoria',
        'andamento_id',
        'ritmo_id',
        'medicao_id',
        'arquivo',
        'versao',
        'status',
        'avanco_fisico',
        'dt_cadastro',
        'cod_fiscal_pi',
        'cod_user_cadastro',
        'dt_aprov_fiscal',
        'cod_user_aprov_fiscal',
        'funcionarios',
        'dt_aprov_tec',
        'cod_user_aprov_analista',
        'dt_envio',
        'cod_user_envio',
        'prev_termino',
        'situacao',
        'cod_lista_envio',
        'cod_medicao_ger',
        'tipo_id'
    ];
    
    public function andamentoItems()
    {
        return $this->hasOne(VistoriaItemAndamento::class, 'vistoria_id', 'id')->latest();
    }
    public function tipos()
    {
        return $this->hasOne(VistoriaTipo::class, 'vistoria_tipo_id', 'tipo_id');
    }
    public function andamentos()
    {
        return $this->hasOne(VistoriaAndamentos::class, 'andamento_id', 'andamento_id');
    }
    public function ritmos()
    {
        return $this->hasOne(VistoriaRitmos::class, 'ritmo_id', 'ritmo_id');
    }
    public function listaEnvioVistoria()
    {
        return $this->hasMany(ListaVistoria::class, 'vistoria_id', 'id');
    }
    public function pi()
    {
        return $this->hasOne(Pi::class, 'id', 'pi_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'cod_user_cadastro');
    }
    public function createVistoriaAbertura($info, $filepath)
    {
        $pi = Pi::where('codigo', $info['codigo_pi'])->first();
        $items = Item::where('id_pi', $pi->id)->first();
        $returnCreate= $this->create([
            'pi_id' => $pi->id,
            'tipo_id' => $info['tipo_vistoria'],
            'codigo'    => $info['codigo_pi'],
            'arquivo' => $filepath,
            'cod_fiscal_pi'=>  $pi->user[0]->id,
            'cod_user_cadastro' => Auth()->user()->id,//validar campo
            'status' => 'cadastro',
            'dt_vistoria' => $info['data_abertura'], //validar se é now(),
            'dt_cadastro' => now(), //validar campo
        ]);

        return $returnCreate->id;
    }
    public function updateVistoriaAbertura($info)
    {
        $pi = Pi::where('codigo', $info['codigo_pi'])->first();
        $item = Item::where('id_pi', $pi->id)->first();

        $returnCreate= $this->create([
            'pi_id' => $pi->id,
            'tipo_id' => $info['tipo_vistoria'],
            'codigo'    => $info['codigo_pi'],
            'status' => 'cadastro',
            'dt_vistoria' => $info['data_abertura'], //validar se é now(),
            'dt_cadastro' => now(), //validar campo
        ]);
        return $returnCreate->id;
    }
    public function createVistoria($info, $filepath)
    {
        $pi = Pi::where('codigo', $info['codigo_pi'])->with('User')->first();
        $returnCreate= $this->create([
            'pi_id' => $pi->id,
            'tipo_id' => $info['tipo_vistoria'],
            'andamento_id' => $info['andamento'],
            'ritmo_id'      => $info['ritmo'],
            'codigo'    => $info['codigo_pi'],
            'dt_vistoria' => $info['data_lo'], //validar se é now(),
            'avanco_fisico'=> str_replace('%',"",str_replace(',','.',$info['media_global'])),
            'arquivo' => $filepath,
            'status' => 'cadastro',
            'dt_cadastro' => now(), //validar campo
            'cod_fiscal_pi'=>  $pi->user[0]->id,
            'funcionarios' => $info['funcionario'],
            'prev_termino' => $info['prev_termino'],
            'cod_user_cadastro' => Auth()->user()->id//validar campo
        ]);
        return $returnCreate->id;
    }
    public function updateVistoria($info)
    {
        $pi = Pi::where('codigo', $info['codigo_pi'])->with('User')->first();

        $returnUpdate = $this->where('id', $info['id'])->update([
            'pi_id' => $pi->id,
            'tipo_id' => $info['tipo_vistoria'],
            'codigo'    => $info['codigo_pi'],
            'dt_vistoria' => $info['data_lo'], //validar se é now(),
            'avanco_fisico'=> str_replace('%', "", str_replace(',', '.', $info['media_global'])),
            'dt_cadastro' => now(), //validar campo
            'cod_fiscal_pi'=> $pi->user[0]->id,
            'funcionarios' => $info['funcionario'],
            'cod_user_cadastro' => Auth()->user()->id//validar campo
        ]);
        return $returnUpdate;
    }
    public static function verifyIfVistoriaExists($piCod)
    {
        //Transformando em um array
        $piCod = preg_replace('/\./', '-', explode('_', $piCod));
        $data = date('Y-m-d', strtotime($piCod[1]));
        $codigo = substr($piCod[0], 0, 4) . '/' . substr($piCod[0], 4, 5);
        return  self::with('pi')
                    ->with('pi.User')
                    ->where('codigo', $codigo)
                    ->where('dt_vistoria', $data)
                    ->where('status', 'cadastro')
                    ->first();
    }
}
