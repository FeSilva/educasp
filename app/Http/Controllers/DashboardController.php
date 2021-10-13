<?php

namespace App\Http\Controllers;

use App\Models\Pi;
use App\Models\Vistoria;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $naoEnviadas = 0;
        $aguardandoRetorno = 0;
        $enviados = 0;
        $pi = Pi::get();
        $vistorias = Vistoria::get();

        foreach ($vistorias as $vistoria) {
            switch ($vistoria->status) {
                case 'cadastro':
                    $naoEnviadas++;
                    break;
                case 'em aprovação':
                    $aguardandoRetorno++;
                    break;
                case 'Enviado':
                    $enviados++;
            }
        }


        $return = [
            'naoEnviados' => $naoEnviadas,
            'emAprovacao' => $aguardandoRetorno,
            'enviado' => $enviados,
            'pis' => count($pi)
        ];

        return view('dashboard.index',compact('return'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
