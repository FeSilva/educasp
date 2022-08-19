<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VistoriasMultiplas extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at','dt_vistoria'];
    protected $fillable = [
        'user_id',
        'pi_id',
        'tipo_id',
        'predio_id',
        'medicao_id',
        'programa',
        'fiscal_user_id',
        'codigo_predio',
        'dt_vistoria',
        'num_orcamento',
        'arquivo',
        'name_archive',
        'status',
        'check_avanco',
        'merge',
        'cod_pi'
    ];

    public function Tipos(){
        return $this->hasOne(VistoriaTipo::class, 'vistoria_tipo_id','tipo_id');
    }

    public function Pi(){
        return $this->hasOne(Pi::class, 'id','pi_id');
    }

    public function Fiscal()
    {
        return $this->hasOne(Users::class, 'id', 'fiscal_user_id');
    }
    public function listaEnvioVistoria()
    {
        return $this->hasMany(ListaVistoriaMultiplos::class, 'vistoria_id', 'id');
    }
    public function Predio(){
        return $this->hasOne(Predios::class, 'id', 'predio_id');
    }
    public function UserCreate(){
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public static function verifyIfVistoriaExists($piCod)
    {
        return self::where('name_archive', $piCod)
            ->where('status', '!=', 'enviado')
            ->first();
    }
}
