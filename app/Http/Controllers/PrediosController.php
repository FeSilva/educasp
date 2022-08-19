<?php

namespace App\Http\Controllers;

use App\Http\Requests\PredioRequest;
use App\Models\Pi;
use App\Models\Predios;
use Couchbase\Exception;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class PrediosController extends Controller
{

    public function index(Request $request, Predios $model)
    {
        if ($request->ajax()) {

            $returnPredios = Predios::latest()->get();
            return Datatables::of($returnPredios)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('predios.store', ['id' => $row->id]) . '"> 
                                                <img src="'.asset("paper").'/img/icons/edit.png" width="30px">
                                            </a> 
                                            <a onclick="deletePredios(' . $row->id . ')"><img src="'.asset("paper").'/img/icons/excluir.png" width="20px"  onclick="deleteUser($usuario[id])">
</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('predios.list');
    }

    // public function index(Predios $model)
    // {
    //     $returnPredios = $model->getPredios();
    //     return view('predios.list',['predios' => $returnPredios->toArray()]);
    // }

    public function store(Predios $model, Request $request)
    {
        $info = $request->all();
        $diretorias = $model->getDiretorias();
        $diretorias = $diretorias->toArray();

        if (isset($info['id'])) {
            $returnPredios = $model->getPredios($info['id'])->toArray();
            $predio = $returnPredios[0];

            $predios = [
                'id' => $predio['id'],
                'codigo' => $predio['codigo'],
                'name' => $predio['name'],
                'diretoria' => $predio['diretoria'],
                'endereco' => $predio['endereco'],
                'telefone' => $predio['telefone'],
                'municipio' => $predio['municipio'],
                'bairro' => $predio['bairro'],
            ];

            return view('predios.store')->with(compact('predios'))->with(compact('diretorias'));
        } else {

            $predios = [
                'id' => '',
                'codigo' => '',
                'name' => '',
                'diretoria' => '',
                'endereco' => '',
                'telefone' => '',
                'municipio' => '',
                'bairro' => '',
            ];

            return view('predios.store')->with(compact('predios'))->with(compact('diretorias'));
        }

    }

    //FUNÇÕES DE CHAMADA PARA MODEL.
    public function create(Predios $model, PredioRequest $request)
    {

        $request->flash();

        $info = $request->all();

        if (isset($info['id'])) {
            try {
                $model->updatePredios($info);
                return redirect()
                    ->back()
                    ->with('success', 'Prédio Atualizado');
            } catch (\Exception $e) {

                return redirect()
                    ->back()
                    ->with('ERROR', $e->getMessage());
            }
        } else {
            try {
                $predio = $model->createPredios($info);
                return redirect('predios/cadastro?id='.$predio)->with('success', 'Prédio Cadastrado');

            } catch (\Exception $e) {

                return redirect()
                    ->back()
                    ->with('ERROR', $e->getMessage());
            }
        }
    }

    public function delete(Predios $model, $id)
    {

        $relation = Pi::where('id_predio', $id)->get();
        $relation = $relation->toArray();

        if (empty($relation)) {

            try {
                $model->deletePredios($id);
                return redirect()
                    ->back()
                    ->with('success', 'Prédio Desativo');
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        }


        return redirect()
        ->back()
        ->with('error', 'Prédio está relacionado a uma PI');
    }
}
