<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Pi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index($codigo)
    {
        $codigo = substr($codigo, 0, 4) . '/' . substr($codigo, 4);
        //Encontrar o Id desse codigo
        $pi = Pi::select('id')->where('codigo', $codigo)->first();
        
        $itens = DB::select(DB::raw("
            select data_calendar.data_vistoria AS data_vistoria,
            data_calendar.codigo AS codigo,
            data_calendar.fiscal AS fiscal,
            data_calendar.link AS link,
            data_calendar.mes AS mes,
            data_calendar.andamento_item AS andamento_item,
            data_calendar.media_global AS media_global,
            data_calendar.previsao_termino AS previsao_termino,
                (select 
                    count(distinct vistorias.prev_termino) from vistorias where (
                        (vistorias.codigo = data_calendar.codigo) and (vistorias.prev_termino < data_calendar.previsao_termino)
                        )
                    ) AS nivel_continuacao from (
                        select vistorias.dt_vistoria AS data_vistoria,
                        vistorias.codigo AS codigo,
                        (case when (users.name is null) 
                        then 'Sem Fiscal' else users.name end) AS fiscal,
                        concat('/pi/cadastro?id=',pis.id) AS link,
                        (case when (month(vistorias.dt_vistoria) = 1) 
                        then '01' when (month(vistorias.dt_vistoria) = 2) 
                        then '02' when (month(vistorias.dt_vistoria) = 3) then '03' when (month(vistorias.dt_vistoria) = 4) 
                        then '04' when (month(vistorias.dt_vistoria) = 5) then '05' when (month(vistorias.dt_vistoria) = 6) 
                        then '06' when (month(vistorias.dt_vistoria) = 7) then '07' when (month(vistorias.dt_vistoria) = 8) 
                        then '08' when (month(vistorias.dt_vistoria) = 9) then '09' when (month(vistorias.dt_vistoria) = 10) 
                        then '10' when (month(vistorias.dt_vistoria) = 11) then '11' when (month(vistorias.dt_vistoria) = 12) 
                        then '12' end) AS mes,
                        vistoria_item_acompanhamento.progress AS andamento_item,
                        vistorias.avanco_fisico AS media_global,
                        vistorias.prev_termino AS previsao_termino from 
                            (((pis join vistorias on((vistorias.pi_id = pis.id))) left join users on((users.id = vistorias.cod_fiscal_pi))) 
                            left join vistoria_item_acompanhamento on((vistoria_item_acompanhamento.vistoria_id = vistorias.id))) 
                            where (pis.id = ".$pi->id.") order by mes,data_vistoria) data_calendar"));
        
        //dd($itens);
        $events = [];
        foreach ($itens as $item) {
           $events[] = [
               'title' => 'Vistoria Agendada',
               'start' => $item->data_vistoria . ' 00:00:00',
               'end' => $item->data_vistoria . '23:59:59'
           ];
        }

        $events = json_encode($events);
        return view('calendar.pi.index', compact('events'));
    }
}
