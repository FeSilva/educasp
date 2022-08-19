<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvioEmail extends Model{

    use HasFactory;

    protected $table = 'envio_email_lista';

    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'lista_id', 'mes','seq'];

}
