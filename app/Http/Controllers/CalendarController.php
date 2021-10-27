<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    private $model;

    public function __construct(Calendar $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $events = [];
        //Colocando eventos fakes
        for ($i = 1; $i <= 10; $i++) {
            $i = $i < 10 ? "0".$i : $i; 
            $data['title'] = "Teste de Evento {$i}";
            $data['start'] = date("Y-m-{$i}");
            $data['end'] = date("Y-m-{$i}");
            $events[] = $this->model->store($data);
        }

        $events = json_encode($events);
        return view('calendar.pi.index', compact('events'));
    }
}
