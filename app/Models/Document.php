<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'document_os';
    protected $fillable = [
        'nome_escola', 'numero_os', 'numero_gestao_social', 'pi',
        'codigo','contrato', 'nome_contratada', 'qtde_vistorias_seguranca_original',
        'qtde_vistorias_gestao_original', 'percentual', 'justificativa',
        'qtde_vistorias_complementar_obra', 'qtde_vistorias_complementar_seguranca', 
        'qtde_vistorias_complementar_gestao'
    ];

    use HasFactory;

    public function store(array $data)
    {
        return $this->create($data);
    }
}
