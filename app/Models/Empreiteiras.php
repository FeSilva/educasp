<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empreiteiras extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $timestamps = true;
    protected $primaryKey = 'id';

    protected $fillable = [
        'fantasia',
        'cnpj',
        'name',
        'enedereco',
        'municipio',
        'bairro',
        'telefone',
        'email',
        'nome_contato',
        'id_user',
    ];

    public function getEmpreiteiras($id = null)
    {
        if(isset($id)){
            return $this->where('id',$id)->get();
        }else{
            return $this->get();
        }
    }

    public function createEmpreiteiras($info)
    {

        $returnCreate = $this->insert([

            // 'cnpj' => $info['cnpj'],
            'name' => $info['name'],
            'cnpj' => $info['cnpj'],
            // 'enedereco' => $info['endereco'],
            // 'municipio' => $info['municipio'],
            // 'bairro' => $info['bairro'],
            'telefone_opcional' => $info['telefone_opcional'],
            'email_opcional' => $info['email_opcional'],
            'nome_contato_opcional' => $info['nome_contato_opcional'],
            'telefone' => $info['telefone'],
            'email' => $info['email'],
            'nome_contato' => $info['nome_contato'],
            'id_user' => $info['id_user']
        ]);

        return $returnCreate;
    }

    public function updateEmpreiteiras($info)
    {

        $returnUpdate = $this->where('id', $info['id'])->update([

            // 'cnpj' => $info['cnpj'],
            'name' => $info['name'],
            'cnpj' => $info['cnpj'],
            // 'enedereco' => $info['endereco'],
            // 'municipio' => $info['municipio'],
            // 'bairro' => $info['bairro'],
            'telefone_opcional' => $info['telefone_opcional'],
            'email_opcional' => $info['email_opcional'],
            'nome_contato_opcional' => $info['nome_contato_opcional'],
            'telefone' => $info['telefone'],
            'email' => $info['email'],
            'nome_contato' => $info['nome_contato'],
            'id_user' => $info['id_user']
        ]);

        return $returnUpdate;
    }


    public function deleteEmpreiteiras($id)
    {
        return $this->find($id)->delete();

    }
}
