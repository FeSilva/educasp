<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUsers extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $table = 'company_users';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_id',
        'user_id',
        'razao_social',
        'fantasia',
        'cnpj',
        'created_at',
        'update_at',
        'actived',
    ];
}
