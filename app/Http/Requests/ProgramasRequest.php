<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Programas;
use Illuminate\Http\Request;
class ProgramasRequest extends FormRequest
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
            'name'    => 'unique:programas,name,'.$request->id
        ];
    }

    public function messages(){
        return [
            "name.unique" => 'Já existe um programa com este nome cadastrado.',
        ];
    }
}
