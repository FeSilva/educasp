<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaEnviosMultiplos extends Model
{
    use HasFactory;
    protected $table = 'lista_envios_multiplos';

    protected $fillable = [
      'user_id',
      'codigo_lista',
      'mes', 'tipo_id'
    ];
}
