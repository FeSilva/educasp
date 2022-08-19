<?php

namespace App\Models\Medicao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicaoFiscais extends Model
{
    use HasFactory;

    protected $table = 'medicao_fiscal';
    protected $primaryKey = 'medicao_fiscal_id';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'medicao_fiscal_id',
        'medicao_id',
        'fiscal_id',
        'fiscal_name',
        'total_medido',
        'valor_medido',
        'total_disponivel',
        'valor_disponivel',
        'total_pendente',
        'valor_pendente',
        'despesas',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
