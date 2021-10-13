<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistoriaItemAndamento extends Model
{
    use HasFactory;

    protected $table = 'vistoria_item_acompanhamento';

    protected $fillable = ['pi_id', 'item_id', 'vistoria_id', 'dt_vistoria', 'progress'];

    public function Items(){
        return $this->hasMany(Item::class,'id','item_id');
    }

    public function createAndamentoItem($vistoria_id, $info)
    {
        $pi = Pi::where('codigo', $info['codigo_pi'])->first();
        $items = Item::where('id_pi', $pi->id)->get();

        //LOOP PARA MAIS DE UM ITEM EM ANDAMENTO
        foreach ($items as $item) {
            if (isset($info['item_' . $item->id])) {
                try {
                    $this->create([
                        'pi_id' => $pi->id,
                        'item_id' => $item->id,
                        'vistoria_id' => $vistoria_id,
                        'dt_vistoria' => now(),
                        'progress' => str_replace(',', '.', $info['item_' . $item->id])
                    ]);
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
        return true;
    }

}
