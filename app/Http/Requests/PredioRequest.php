<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class PredioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'codigo'    => 'required|unique:predios,codigo,'.$request->id,
            'name'      => 'required',
            'diretoria' => 'required',
            'endereco'  => 'required',
            'municipio' => 'required',
            'bairro'    => 'required'
        ];
    }

    public function messages(){
        return [
            'codigo.required'    => 'Por favor digite o código do prédio para o cadastro.',
            'codigo.unique'      => 'Já existe um prédio com este código cadastrado.',
            'name.required'      => 'Qual o nome do prédio ?',
            'diretoria.required' => 'Escolha uma diretoria',
            'endereco.required'  => 'Em que endereço onde está localizado ?',
            'municipio.required' => 'Informe o municipio',
            'bairro.required'    => 'Informe o bairro'
        ];
    }
}
