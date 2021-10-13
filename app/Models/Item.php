<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'items';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_pi',
        'num_item',
        'dt_assin_ois',
        'tipo_item',
        'dt_abertura',
        'prazo',
        'valor',
        'descricao_item',
    ];

    public function AndamentoItems(){
        return $this->hasOne(VistoriaItemAndamento::class,'item_id','id')->latest();
    }

    public function createItem($info)
    {

        return $this->insert([
            'id_pi' => $info['id_pi'],
            'num_item' => $info['item_pi'],
            'dt_assin_ois' => $info['dt_assin_ois'],
            'tipo_item'     => $info['tipo_item'],
            'dt_abertura' => $info['dt_abertura'],
            'prazo' => $info['prazo_item'],
            'valor' => $info['valor_item'],
            'descricao_item' => $info['observacoes'],
        ]);


    }

    public function updateItem($info)
    {

        return $this->where('id',$info['id'])->update([
            'id_pi' => $info['id_pi'],
            'num_item' => $info['item_pi'],
            'dt_assin_ois' => $info['dt_assin_ois'],
            'dt_abertura' => $info['dt_abertura'],
            'tipo_item'     => $info['tipo_item'],
            'prazo' => $info['prazo_item'],
            'valor' => $info['valor_item'],
            'descricao_item' => $info['observacoes'],
        ]);


    }

    public function deleteItem($id){

        return $this->find($id)->delete();
    }
}
