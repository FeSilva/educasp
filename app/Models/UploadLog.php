<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadLog extends Model
{
    use HasFactory;

    protected $table = 'upload_logs';

    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = ['user_id','arquivo', 'status', 'data_envio'];

    public function storeLog($data)
    {
        return $this->create([
            'user_id' => Auth()->user()->id,
            'arquivo' => $data['arquivo'],
            'status' => $data['status'],
            'data_envio' => $data['data_envio']
        ]);
    }

    public function user(){
        return $this->hasMany(User::class,'id','user_id');
    }
}
