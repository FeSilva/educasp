<?php

namespace App\Http\Controllers;

use App\Http\Requests\VistoriaRequest;
use App\Models\Pi;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Vistoria;
use App\Models\VistoriaTipo;
use Illuminate\Http\Request;

class VistoriaController extends Controller
{
    private $model;

    public function __construct(Vistoria $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $vistoriaTipos = VistoriaTipo::where('status', 1)->get();
        return view('vistorias.store', compact('vistoriaTipos'));
    }


    public function getPi($codigo = '2020/00978')
    {
        return (new Pi())->getpi($codigo);
    }
}
