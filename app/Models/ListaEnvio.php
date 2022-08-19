<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListaEnvio extends Model
{
    use HasFactory;
    protected $table = 'lista_envios';
    protected $primaryKey = 'id';
    protected $fillable = ['codigo_lista', 'mes', 'user_id', 'status'];

    public function listaVistoria()
    {
        return $this->hasMany(ListaVistoria::class, 'lista_id', 'id');
    }

    public function usuario()
    {
        return $this->hasMany(Users::class,'id','user_id');
    }
}
