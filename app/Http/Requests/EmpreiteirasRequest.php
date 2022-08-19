<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpreiteirasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function rules()
    {
        return [

            'name' => 'required',
            'telefone' =>  'required',
            'email' => 'required',
            'nome_contato' =>  'required',
            'id_user' =>  'required'
        ];
    }

    public function messages()
    {
        return [

            'name.required' => 'Por favor, informe o nome da empreiteira.',
            'telefone.required' =>  'Por favor, informe o telefone.',
            'email.required' => 'Por favor, informe seu melhor e-mail',
            'nome_contato.required' =>  'Por favor, informe um nome para contato',
            'id_user.required' =>  'Selecione um usuário para contato'
        ];
    }

}
