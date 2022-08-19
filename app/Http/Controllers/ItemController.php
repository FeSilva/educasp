<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Item;

class ItemController extends Controller
{
    public function create(ItemRequest $request, Item $model){
        $info = $request->all();

        if(isset($info['id'])){
            try {
                $model->updateItem($info);
                return redirect('pi/cadastro?id='.$info['id_pi'])
                    ->with('success', 'Item Atualizado');
            }catch(\Exception $e) {
                return redirect()
                    ->with('error', $e->getMessage());
            }
        }else{
            try {
                $return = $model->createItem($info);
                return redirect('pi/cadastro?id='.$info['id_pi'])
                    ->with('success', 'Item Cadastrado');
            }catch(\Exception $e) {

                return redirect('pi/cadastro?id='.$info['id_pi'])
                    ->with('error', $e->getMessage());
            }
        }
    }

    public function store(Item $model, Request $request){

        $info = $request->all();


        $items = [ //Criar Table/model para tipos de items ?
            'Reforma' => 'Reforma',
            "Ampliação" => 'Ampliação',
            "Adequação" => 'Adequação',
            "Construção" => 'Construção',
            "Obra Nova" => 'Obra Nova',
            "Demolição" => 'Demolição',
            "Paisagismo" => 'Paisagismo'
        ];

        if(isset($info['id'])){
            $itemArray = $model->where('id',$info['id'])->get()->toArray();
            $pi = [
            'id'        => $itemArray[0]['id'],
            'id_pi'         => $itemArray[0]['id_pi'],
            'num_item' => $itemArray[0]['num_item'],
            'tipo_item' => $itemArray[0]['tipo_item'],
            'valor' => $itemArray[0]['valor'],
            'dt_assin_ois' => $itemArray[0]['dt_assin_ois'],
            'dt_abertura' => $itemArray[0]['dt_abertura'],
            'prazo' => $itemArray[0]['prazo'],
            'descricao_item' => $itemArray[0]['descricao_item'],
            ];

        }else{
            $pi= [
                'id'        => '',
                'id_pi'     => $info['id_pi'],
                'num_item' => '',
                'tipo_item' => '',
                'valor' => '',
                'dt_assin_ois' => '',
                'dt_abertura' => '',
                'prazo' => '',
                'descricao_item' => '',
            ];
        }


        return view('pi.item')->with(compact('pi'))->with(compact('items'));

    }

     //Não havera delete de pi e item.. apenas edição / Validar regras para edição.
    public function delete(Item $model, $id, $id_pi){


        try {
            $delete = $model->deleteItem($id);
            return redirect('pi/cadastro?id='.$id_pi)
                ->with('success', 'Item desativado');
        }catch(\Exception $e) {

            return redirect('pi/cadastro?id='.$id_pi)
                ->with('error', $e->getMessage());
        }
        //return redirect()->route('pi.store',['id' => $id_pi]);
    }


}
