<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Educa SP - Relatório de Medição </title>
    <style>
        table {
            table-layout: fixed;
            width: 100%;

        }

        .img-logo{
            width: 170px;
        }
        p{
            font-size:14px;
        }

        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
          }
          
          #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
          }
          
          #customers tr:nth-child(even){background-color: #f2f2f2;}
          
          #customers tr:hover {background-color: #ddd;}
          
          #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4b5573;
            color: white;
          }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-12">
            <img src="{{storage_path('app/public/Logo_fde.jpg')}}" class="img-logo">
            <h1><center>Relatório de Despesas<center></h1>
            <h4>{{ $fiscal->name }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @php 
                $inicio = date_create($medicao->dt_ini);
                $fim = date_create($medicao->dt_fim );
            @endphp
            <p>Periodo entre {{ date_format($inicio, 'd/m/Y') }} Até {{ date_format($fim, 'd/m/Y') }} - {{ $medicao->name }}</p>
        </div>
    </div>
    @foreach ($listagem as $key => $results)
            <div class="row">
                <div class="col-12">
                    <h3>{{ $key == 'realizadas' ? 'Vistorias Realizadas' : 'Despesas' }}</h3>
                </div>
            </div>      
            <div class="row">
                <div class="col-md-12">
                    @php 
                        $count = 1;
                        $amountTotal = 0;
                        $qtdTotal = 0;
                    @endphp
                    <table class="table" id="customers" style="
                                padding: 0.5rem;
                                text-align: center;">
                        <thead>  
                            <tr>
                                @foreach($results['table']['theads'] as $keyTitles => $titles)
                                <th>{{ $titles }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                    
                            @foreach($results['table']['tbody'] as $keyBody => $column)

                                @if ($key == 'despesas')
                                    @php 
                                        $qtdTotal++;
                                        $amountTotal += $column->amount;
                                        $created_at = date_create($column->created_at);
                                    @endphp
                                    <tr>
                                        <td>{{ $column->type }}</td>
                                        <td>{{ date_format($created_at, 'd/m/Y') }}</td>
                                        <td>{{ $column->amount }}</td>
                                        <td>{{ $column->id }}</td>
                                    </tr>
                                @else
                                    @php 
                                        $qtdTotal++;
                                    @endphp
                                    <tr>
                                        <td>{{ $column->codigo }}</td>
                                        <td>{{ $column->predio_name }}</td>
                                        <td>{{ $column->tipo_vistoria }}</td>
                                        <td>{{ $column->data_vistoria }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            
                            <tr>
                                <td></td>
                                <td align='center'><strong>Total:</strong></td>
                                <td><strong>{{ $qtdTotal }}</strong></td>
                                <td><strong>R$ {{ number_format($amountTotal, 2, ",", ".") }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <hr>
                </div>
            </div>
    @endforeach
</body>
</html>
