<?php

namespace App\Models\Despesas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vistorias extends Model
{
    use HasFactory;

    protected $table = 'despesas_vistorias';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'id',
        'despesa_id', //Usuário criado
        'vistoria_id',
        'type_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
