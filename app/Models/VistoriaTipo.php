<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistoriaTipo extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $primaryKey = 'vistoria_tipo_id';

    protected $fillable = ['name', 'sigla','description','price','amount_to_receive', 'status'];
}
