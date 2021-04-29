<?php

namespace App\Http\Controllers;

use App\Http\Requests\VistoriaRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use Illuminate\Http\Request;
//use App\Models\Vistorias;

class VistoriaController extends Controller
{

    public function index(){

        return view('vistorias.store');
    }
}
