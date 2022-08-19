<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Predios;
use App\Models\Users;
use App\Models\Empreiteiras;
use App\Models\Item;

class Pi extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'codigo',
        'id_predio',
        'id_user',
        'id_empreiteira',
        'endereco',
        'diretoria',
        'tipo_contratacao',
        'dt_assinatura',
        'programa',
        'objeto_pi',
        'municipio',
        'cod_pi',
        'prazo_total',
        'valor_total',
        'bairro',
        'rv',
        'descricao',
        'telefone',
        'numero_contrato',
        'numero_os',
        'qtde_vistorias_mes',
        'nome_contratada',
        'numero_gestao_social'
    ];
    public function getPi($id = null)
    {
        if (isset($id)) {
            return $this->where("id", $id)->get();
        }
        return $this->get();
    }
    public function vistorias()
    {
        return $this->hasMany(Vistoria::class);
    }
    public function Items()
    {
        return $this->hasMany(Item::class, 'id_pi', 'id');
    }
    public function Empreiteiras()
    {
        return $this->hasMany(Empreiteiras::class, 'id', 'id_empreiteira');
    }
    public function Predios()
    {
        return $this->hasMany(Predios::class, 'id', 'id_predio');
    }
    public function User()
    {
        return $this->hasMany(User::class, 'id', 'id_user');
    }
    public function createPi($info)
    {
        // $codigo = explode('/',$info['codigo']);
        // $codigo = trim($codigo[0])."/".trim($codigo[1]);
        $returnCreate = $this->create([
            'codigo'            => $info['codigo'],
            'id_predio'         => $info['id_predio'],
            'dt_assinatura'     => $info['assinatura'],
            'id_user'           => $info['fiscais'],
            'tipo_contratacao'  => $info['contratacao'],
            'id_empreiteira'    => $info['empreiteiras'],
            'programa'          => $info['programa'],
            'rv'                => $info['rv'],
            'objeto_pi'         => $info['objeto_pi'],
            'valor_total'       => $info['valor_total'],
            'prazo_total'       => $info['prazo_total'],
            'descricao'         => $info['descricao'],
            'numero_contrato'   => $info['numero_contrato'],
            'numero_os'         => $info['numero_os'],
            'qtde_vistorias_mes' => $info['qtde_vistorias_mes'],
            'nome_contratada' => $info['nome_contratada'],
            'numero_gestao_social' => $info['numero_gestao_social'],
        ]);
        return  $returnCreate->id;
    }

    public function updatePi($info)
    {
        $codigo = explode('/', $info['codigo']);
        $codigo = trim($codigo[0])."/".trim($codigo[1]);
        $returnUpdate = $this->where('id',$info['id'])->update([
            'codigo'            =>  $codigo,
            'id_predio'         => $info['id_predio'],
            'dt_assinatura'        => $info['assinatura'],
            'id_user'            => $info['fiscais'],
            'tipo_contratacao'       => $info['contratacao'],
            'programa'          => $info['programa'],
            'objeto_pi'         => $info['objeto_pi'],
            'rv'                => $info['rv'],
            'id_empreiteira'    => $info['empreiteiras'],
            'valor_total'       => $info['valor_total'],
            'prazo_total'       => $info['prazo_total'],
            'descricao'         => $info['descricao'],
            'numero_contrato'   => $info['numero_contrato'],
            'numero_os'         => $info['numero_os'],
            'qtde_vistorias_mes' => $info['qtde_vistorias_mes'],
            'nome_contratada' => $info['nome_contratada'],
            'numero_gestao_social' => $info['numero_gestao_social'],
        ]);
        return  $returnUpdate;
    }
    public function deletePi($id)
    {
        return $this->find($id)->delete();
    }
}
