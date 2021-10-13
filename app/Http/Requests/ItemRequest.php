<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'item_pi' => 'required',
            'tipo_item' => 'required',
            'valor_item' => 'required',
            'dt_assin_ois' => 'required',
            'prazo_item'    => 'required'
        ];
    }

    public function messages(){
        return [
            'item_pi.required' => 'Preencha o campo item do Pi.',
            'tipo_item.require'=> 'Preencha o campo Tipo Item',
            'valor_item.required'        => 'Preencha o campo de valor do item',
            'dt_assin_ois.required'=> 'Preencha o campo de data de OIS',
            'prazo_item.required'    => 'Preencha um prazo para o item'
        ];
    }
}
