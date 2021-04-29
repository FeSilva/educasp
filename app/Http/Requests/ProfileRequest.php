<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name'      => ['required', 'min:3'],
            'email'     => ['required', 'email', Rule::unique((new User)->getTable())->ignore(auth()->id())],
            'grupo'     => ['required'],
            'password' => ['confirmed','min:6']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome precisa ser preenchido.',
            'email.required' => 'O campo email precisa ser preenchido.',
            'password.confirmed' => 'As senhas não são identicas',
            'password.min'       => 'A senha deve conter ao menos 6 caracters'
        ];
    }
}
