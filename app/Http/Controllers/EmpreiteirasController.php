<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpreiteirasRequest;
use App\Models\Empreiteiras;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Pi;

class EmpreiteirasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $returnEmpreiteiras = Empreiteiras::latest()->get();
            return Datatables::of($returnEmpreiteiras)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('empreiteiras.store', ['id' => $row->id]) . '">
                         <img src="'.asset("paper").'/img/icons/edit.png" width="30px">
                    </a> 
                     <a onclick="deleteEmpreiteiras(' . $row->id . ')"><img src="'.asset("paper").'/img/icons/excluir.png" width="20px"  onclick="deleteEmpreiteiras($row->id)">
                    </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('empreiteiras.list');
    }

    // public function index(Empreiteiras $model){
    //     $empreiteiras = $model->getEmpreiteiras();

    //     return view('empreiteiras.list',['empreiteiras' => $empreiteiras]);
    // }

    public function store(Empreiteiras $model, Request $request)
    {
        $info = $request->all();

        if (isset($info['id'])) {
            $empreiteiras = $model->getEmpreiteiras($info['id']);
            $empreiteira = $empreiteiras->toArray();

            return view('empreiteiras.store', ['empreiteira' => $empreiteira[0]]);
        } else {

            return view('empreiteiras.store', ['empreiteira' => '']);
        }
    }

    //FUNÇÕES DE CHAMADA PARA MODEL.
    public function create(Empreiteiras $model, EmpreiteirasRequest $request)
    {
        $info = $request->all();
        if (isset($info['id'])) {
            try {
                $model->updateEmpreiteiras($info);

                return redirect()
                    ->back()
                    ->with('success', 'Empreiteira atualizada');
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        } else {
            try {
                $empreiteira = $model->createEmpreiteiras($info);
                return redirect('empreiteiras/cadastro?id='.$empreiteira)->with('success', 'Empreiteira Cadastrada');

            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        }
    }

    public function delete(Empreiteiras $model, $id)
    {
        $relation = Pi::where('id_empreiteira', $id)->get();
        $relation = $relation->toArray();

        if (empty($relation)) {
            try {
                $model->deleteEmpreiteiras($id);
                return redirect()
                ->back()
                ->with('error', 'Empreiteira desativada.');
            } catch (\Exception $e) {
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        }

        return redirect()
        ->back()
        ->with('error', 'Empreiteira está relacionado a uma PI');
    }
}
