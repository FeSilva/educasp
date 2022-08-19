<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocoloVistorias extends Model
{
    use HasFactory;
    protected $table = 'protocolo_vistorias';
    protected $fillable = [
        'protocolo_id',
        'vistoria_id',
    ];

    public function vistorias(){
        return $this->hasMany(Vistoria::class,'id','vistoria_id');
    }

    public function protocolos(){
        return $this->hasMany(Protocolo::class,'id','protocolo_id');
    }
}
