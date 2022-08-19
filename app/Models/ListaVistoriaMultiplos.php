<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaVistoriaMultiplos extends Model
{
    use HasFactory;
    protected $table = 'lista_vistoria_envios_multiplos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'lista_id',
        'vistoria_id',
    ];
    public function lista()
    {
        return $this->belongsTo(ListaEnviosMultiplos::class, 'lista_id', 'id');
    }
    public function vistorias()
    {
        return $this->belongsToMany(VistoriasMultiplas::class, 'vistorias_multiplas', 'id', 'vistoria_id');
    }
}
