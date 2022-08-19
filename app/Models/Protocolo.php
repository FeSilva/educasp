<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'data',
        'status'
    ];

    public function protocoloVistorias(){
        return $this->hasMany(ProtocoloVistorias::class);
    }

    public function getVistorias()
    {
        return Vistoria::select('id', 'pi_id', 'codigo', 'arquivo')
                                ->where('status', null)
                                ->with('pi', function($query) {
                                    $query->select('id', 'id_predio')
                                        ->with('Predios', function($query) {
                                            $query->select('id', 'name');
                                        });
                                })
                                ->get();
    }
}
