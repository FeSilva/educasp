<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|'.Rule::unique('users')->ignore($this->request->get('id')),
            'grupoUser' => 'required',
            //'cod_user_fde' => '',
            'password' => 'confirmed|min:6'
        ];
    }

    public function messages()
    {
        return [
            //'cod_user_fde.required' => 'Por favor informe o código FDE do usuário',
            'name.required' => 'Qual nome do usuário ?',
            'email.required' => 'informe o email do usuário',
            'email.unique'  => 'Já existe um usuário com este email cadastro.',
            'grupoUser.required' => 'Selecione um grupo para o usuário',
            'password.confirmed' => 'As senhas não são identicas',
            'password.min'       => 'A senha deve conter ao menos 6 caracters'
        ];
    }
}
