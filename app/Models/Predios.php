<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pi;
class Predios extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'codigo',
        'name',
        'endereco',
        'diretoria',
        'municipio',
        'bairro',
        'telefone',
        'id_user'
    ];


    public function getPredios($id = null){
        if($id){
            return $this->where('id',$id)->get(); //Retirar limit após paginação.
        }else{
            return $this->get();
        }
    }

    public function getCarregamento($codigoPredio){
        return $this->where('codigo',$codigoPredio)->get();
    }

    public function getDiretorias(){
        return $this->select('diretoria')->where('diretoria','<>','')->orderBy('diretoria','asc')->distinct()->get();
    }


    public function createPredios($info){

        $returnCreate = $this->create([
            'codigo'    => $info['codigo'],
            'name'      => $info['name'],
            'endereco'  => $info['endereco'],
            'diretoria' => $info['diretoria'],
            'municipio' => $info['municipio'],
            'telefone'  => $info['telefone'],
            'bairro'    => $info['bairro'],
            'id_user'   => $info['id_user']
        ]);

        return  $returnCreate->id;
    }

    public function updatePredios($info){


        $returnUpdate = $this->where('id',$info['id'])->update([
            'codigo'    => $info['codigo'],
            'name'      => $info['name'],
            'endereco'  => $info['endereco'],
            'diretoria' => $info['diretoria'],
            'municipio' => $info['municipio'],
            'telefone'  => $info['telefone'],
            'bairro'    => $info['bairro']
        ]);

        return  $returnUpdate;
    }

    function deletePredios($id){
        return $this->find($id)->delete();
    }

}
