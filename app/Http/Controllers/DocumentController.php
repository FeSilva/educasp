<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {

        $pdf = PDF::loadView('documents.os', [])->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('document_os.pdf');
    }
}
