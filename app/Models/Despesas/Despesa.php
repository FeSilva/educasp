<?php

namespace App\Models\Despesas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $table = 'despesas';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'dt_recibo',
        'amount', //Usuário criado
        'type',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
