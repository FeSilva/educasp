<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pi;
use Illuminate\Http\Request;
class PiRequest extends FormRequest
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
            'codigo'    => 'required|unique:pis,codigo,'. $request->id,
            'codigo_predio' => 'required',
            'fiscais' => 'required',
            'empreiteiras' => 'required',
            'assinatura' => 'required',
            'valor_total' => 'required',
            'prazo_total' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'Informe o código do PI',
            'codigo.unique'     => 'Já existe um código para o PI cadastrado.',
            'codigo_predio.required' => 'Informe o codigo do prédio',
            'fiscais.required' => 'Selecione um usuário para representante fiscal.',
            'empreiteiras.required' => 'Selecione uma Empreiteira',
            'assinatura.required' => 'Por favor informe o campo assinatura.',
            'valor_total.required' => 'Por favor informe o valor total desta PI',
            'prazo_total.required' => 'Por favor informe o prazo total desta PI'
        ];
    }
}
