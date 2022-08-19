<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocolosMultiplos extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'codigo',
        'data',
        'status',
        'tipo_id'
    ];

    public function protocoloVistoriasMultiplas(){
        return $this->hasMany(ProtocolosMultiplosVistoria::class,'protocolo_id','id');
    }
}
