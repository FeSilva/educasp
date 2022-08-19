<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProtocolosMultiplosVistoria extends Model
{
    use HasFactory;
    protected $table = 'protocolosMultiplos_vistorias';
    protected $primaryKey = 'id';
    protected $fillable = ['protocolo_id','vistoria_id'];

    public function VistoriasMultiplos(){
        return $this->hasMany(VistoriasMultiplas::class,'id','vistoria_id');
    }

    public function ProtocolosMultiplos(){
        return $this->hasMany(ProtocolosMultiplos::class,'id','protocolo_id');
    }
}
