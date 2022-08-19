<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
class Users extends Model
{

    use HasFactory;

    use SoftDeletes;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'name',
        'email',
        'email_pessoal',
        'password',
        'grupo',
        'ativo',
        'empresa',
        'celular',
        'celular_profissional',
        'obs'
    ];




    public function getUsers($id = null){
        if( $id ) {
            return $this->where('id', $id)->get();
        }else{
            return $this->where('ativo', '1')->get();
        }
    }

    public function deleteUser($id){
        return $this->find($id)->delete();
    }

    public function getSelectFiscal(){
        return $this->where("grupo","fiscal")->get();
    }

    public function createUser($info){


        $returnCreate = $this->create([
            'name' => $info['name'],
            'email' => $info['email'],
            'grupo' => $info['grupoUser'],
            'celular' => $info['celular'],
            'cod_user_fde' => $info['cod_user_fde'],
            'ativo'    => $info['status'],
            'obs'   => $info['observacoes'],
            'empresa' => 'JHE',
            'password' => Hash::make($info['password']),
        ]);

        return  $returnCreate->id;
    }

    public function updateUser($info){

        if(isset($info['password'])){
            return $this->where('id',$info['id'])->update([
                'name' => $info['name'],
                'email' => $info['email'],
                'grupo' => $info['grupoUser'],
                'celular' => $info['celular'],
                'cod_user_fde' => $info['cod_user_fde'],
                'ativo'    => $info['status'],
                'obs'=> $info['observacoes'],
                'empresa' => 'JHE',
                'password' => Hash::make($info['password']),
            ]);
        }else{
            return $this->where('id',$info['id'])->update([
                'name' => $info['name'],
                'email' => $info['email'],
                'grupo' => $info['grupoUser'],
                'ativo'    => $info['status'],
                'cod_user_fde'  => $info['cod_user_fde'],
                'celular' => $info['celular'],
                'obs'=> $info['observacoes'],
                'empresa' => 'JHE'
            ]);
        }

    }
}
