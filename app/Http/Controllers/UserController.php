<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */

    //  public function index(Request $request){
    //     if($request->ajax()){

    //         $returnPredios = Users::latest()->get();
    //         return Datatables::of($returnPredios)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 $btn = '<a href="'.route('usuarios.store',['id' => $row->id]).'" class="btn btn-primary">Editar</a> <a onclick="deleteUsuarios('.$row->id.')" class="btn btn-warning">Desativar</a>';
    //                 return $btn;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //         }

    //         return view('usuario.list');
    //  }
     public function index(Users $model)
     {
         if(auth()->user()->grupo == 'gestor' ||  auth()->user()->grupo == 'analista'){
            $usuarios = Users::get();
         }else{
             $usuarios = $model->getUsers();
         }
         return view('usuario.list', ['usuarios' => $usuarios->toArray()]);
     }


    public function store(Users $model, Request $request) //Regra aplicada para deixar a mesma tela de cadastro como a tela de edição.
    {
        $info = $request->all();

        $grupos = [ //Criar Model pra grupo de usuários
            'analista' => 'Analista',
            'fiscal' => 'Fiscal',
            'gestor' => 'Gestor',
            'gestao_social' => 'Gestão Social',
            'operacional_tecnico' =>'Operacional Técnico'
        ];
        $status = [
            '0' => 'Inativo',
            '1' => 'Ativo'
        ];

        if (isset($info['id'])) {
            $usuarioArray = $model->getUsers($info['id']);
            $usuario = $usuarioArray[0];

            $usuarios =[
                'id'    => $usuario['id'],
                'name' => $usuario['name'],
                'email' => $usuario['email'],
                'grupo' => $usuario['grupo'],
                'celular' => $usuario['celular'],
                'observacoes'    => $usuario['obs'],
                'cod_user_fde' => $usuario['cod_user_fde'],
                'ativo'             => $usuario['ativo']
            ];

            return view('usuario.store')->with(compact('usuarios'))->with(compact('grupos'))->with(compact('status'));
        } else {


            $usuarios =[
                'id'    => '',
                'name' => '',
                'email' => '',
                'grupo' => '',
                'celular' => '',
                'cod_user_fde' => '',
                'observacoes'    => '',
                'ativo'          => ''
            ];

            return view('usuario.store')->with(compact('usuarios'))->with(compact('grupos'))->with(compact('status'));;
        }
    }

    public function perfil()
    {

        return view("usuario.perfil");
    }

    //FUNÇÕES DE CHAMADA PARA MODEL.
    public function create(Users $model, UserRequest $request)
    {
        $info = $request->all();
        $request->flash();

        //$validation = $this->validate($request, $rules, $messages);


        if (isset($info['id'])) {
            try{
                $model->updateUser($info);
                /*$usuario = $model->getUsers($info['id']);
                $usuarios = $usuario->toArray();
                return redirect()->route('usuarios.list')->with(compact('usuarios'));*/
                return redirect()
                    ->back()
                    ->with('success', 'Usuário Atualizado');
            }catch(\Exception $e){
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        } else {

            try{
                $user = $model->createUser($info);
                return redirect('usuarios/cadastro?id='.$user)
                    ->with('success', 'Usuário Cadastrado');

            }catch(\Exception $e){
                return redirect()
                    ->back()
                    ->with('error', $e->getMessage());
            }
        }

        //return  back()->withInput()->withErrors($request); //Redirect necessário pois ao realizar uma inserção se apertar F5 o registro era inserido novamente.

    }

    public function delete(Users $model, $id)
    {
        try{
            $model->deleteUser($id);
            return redirect()
                    ->back()
                    ->with('success', 'Usuário Desativado');
        }catch(\Exception $e){
            return redirect()
            ->back()
            ->with('error', $e->getMessage());
        }
    }

    public function desativar(Users $model, $id)
    {
        try{
            Users::where('id',$id)->update(['ativo' => 0]);
            return redirect()
                ->back()
                ->with('success', 'Usuário Desativado');
        }catch(\Exception $e){
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }


    public function ativar(Users $model, $id)
    {
        try{
            Users::where('id',$id)->update(['ativo' => 1]);
            return redirect()
                ->back()
                ->with('success', 'Usuário Ativado');
        }catch(\Exception $e){
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
