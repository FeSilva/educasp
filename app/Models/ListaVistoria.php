<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaVistoria extends Model
{
    use HasFactory;
    protected $table = 'lista_vistoria_envios';
    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'lista_id',
        'vistoria_id',
    ];

    public function lista()
    {
        return $this->belongsTo(ListaEnvio::class, 'lista_id', 'id');
    }
    public function vistorias()
    {
        return $this->belongsToMany(Vistoria::class, 'vistorias', 'id', 'vistoria_id');
    }
}
