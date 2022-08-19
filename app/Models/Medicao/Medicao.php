<?php

namespace App\Models\Medicao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicao extends Model
{
    use HasFactory;


    protected $table = 'medicao';
    protected $primaryKey = 'medicao_id';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'qtd_vinculadas',
        'user_by', //Usuário criado
        'name',
        'dt_ini',
        'dt_fim',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
