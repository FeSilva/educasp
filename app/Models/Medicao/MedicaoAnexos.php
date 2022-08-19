<?php

namespace App\Models\Medicao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicaoAnexos extends Model
{
    use HasFactory;

    protected $table = 'medicao_anexo';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'id',
        'medicao_id',
        'fiscal_id',
        'name',
        'size',
        'amount',
        'path',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
