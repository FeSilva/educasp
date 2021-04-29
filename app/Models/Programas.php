<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Programas extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
    ];

    public function getProgramas($id = null)
    {
        if(isset($id)){
            return $this->where('id',$id)->get();
        }else{
            return $this->get();
        }
    }

    public function createProgramas($name){
        $returnPrograma =  $this->create([
            'name'=> $name,
        ]);

        return  $returnPrograma->id;
    }
}
