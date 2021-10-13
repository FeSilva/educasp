<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Pi;
use App\Models\Item;
use App\Models\Vistoria;

class CadastroVistoriaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $vistorias = Vistoria::where('codigo', $this->codigo_pi)->orderBy('id','desc')->first();



        if (!isset($this->id)) {
            if ($this->tipo_vistoria == 1) {

                $rules += [
                    'data_abertura' => 'required',
                    //'arquivo_folha' => 'required',
                ];
                session()->flash('tipo_vistoria', '1');
            }

            if ($this->tipo_vistoria == 2 || $this->tipo_vistoria == 3) {
                $rules += [
                    'data_lo' => 'required',
                    //'arquivo_lo' => 'required', Retirado obrigatoriedade de anexo
                    'funcionario' => 'required',
                    'andamento' => 'required',
                    'ritmo' => 'required',
                    'media_global' => 'required',
                    'prev_termino' => 'required',
                ];

                /*$pi = Pi::where('codigo', $this->codigo_pi)->get();
                $items = Item::where('id_pi', $pi[0]->id)->get();

                foreach ($items as $item) {
                    $rules += [
                        'item[' . $item->id . ']' => 'required',
                    ];
                }*/

                if ($this->tipo_vistoria == 2) {
                    session()->flash('tipo_vistoria', '2');
                } else {
                    session()->flash('tipo_vistoria', '3');
                }

            }
        }
        return $rules;
    }


    public function messages()
    {
        $messages = [];

        $messages += [
            'arquivo_folha.required' => 'O arquivo é obrigatório',
            'arquivo_lo.required' => 'O arquivo de LO é obrigatório',
            'funcionario.required' => 'Digite a quantidade de funcionarios',
            'ritmo.required' => 'Selecione um ritmo.',
            'prev_termino.required' => 'A data de término é obrigatório',
            'media_global.required' => 'A média global é obrigatória.',
            'andamento.required' => 'Selecione um andamento',
            'data_abertura.required' => 'Por favor, selecione uma data para abertura',
            'data_lo.required' => 'Por favor, selecione uma data para fiscalização',
            //'data_lo.after_or_equal' => 'Já existe uma vistoria cadastrada para este PI em menos de 5 dias.',
            //'data_abertura.after_or_equal' => 'Já existe uma vistoria cadastrada para este PI em menos de 5 dias.'

        ];


        /*$processoIntervencao = Pi::where('codigo', $this->codigo_pi)->get();
        $items = Item::where('id_pi', $processoIntervencao[0]->id)->get();

        foreach ($items as $item) {
            $thisItemProgressLast = (int)$this->progressLast[$item->id];
            $thisItemProgressNow = number_format((int)$this->item[$item->id], 2, ".", ",");

            if ($thisItemProgressLast > $thisItemProgressNow) {
                $messages += [
                    'item[' . $item->id . ']' => 'Valor não pode ser menor que o anterior: ' . $thisItemProgressLast,
                ];

                session()->flash('item[' . $item->id . ']', 'Valor não pode ser menor que o anterior: ' . $thisItemProgressLast);
            } else {
                $messages += [
                    'item[' . $item->id . ']' => 'Por favor digita uma porcentagem de andamento da obra.',
                ];
                session()->flash('item[' . $item->id . ']', 'Por favor digita uma porcentagem de andamento da obra.');
            }

        }*/
        return $messages;
    }
}
