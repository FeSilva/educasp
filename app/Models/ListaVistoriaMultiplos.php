<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaVistoriaMultiplos extends Model
{
    use HasFactory;
    protected $table = 'lista_vistoria_envios_multiplos';

    protected $fillable = [
      'lista_id',
      'vistoria_id',
    ];
}
