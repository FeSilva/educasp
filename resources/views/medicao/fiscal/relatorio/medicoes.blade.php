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
            <h1><center>Relatório de Medições</cecnter></h1>
            <h4>{{ $company->razao_social }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @php 
                $inicio = date_create($medicao->dt_ini);
                $fim = date_create($medicao->dt_fim );
            @endphp
            <strong>{{ $medicao->name }}</strong>
            <br />
            <p>Periodo entre <strong>{{ date_format($inicio, 'd/m/Y') }}</strong> Até <strong>{{ date_format($fim, 'd/m/Y') }}</strong></p>
        </div>
    </div>
 
    @foreach ($medicoesDetalhes['table']['tbody'] as $tipo => $tbody)
    @php 
        $count = 1;
        $amountTotal = 0;
        $qtdTotal = 0;
    @endphp
            <div class="row">
                <div class="col-12">
                    <h3>{{ $tipo }}</h3>
                </div>
                <div class="col-12">
                    <small style="margin-left:10px; color:red;"><strong>Valor por vistoria {{ $tbody['values'][0]['amount'] }} reais.</strong></small>
                </div>
            </div>      
            <div class="row">
                <div class="col-md-12">
                
                    <table class="table" id="customers" style="
                                padding: 0.5rem;
                                text-align: center;">
                        <thead>  
                            <tr>
                                @foreach($medicoesDetalhes['table']['theads'] as $key => $titles)
                                <th>{{ $titles }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tbody['values'] as $key => $column)
                        
                                @php 
                                    $amountTotal += 43.00; 
                                    $dt_vistoria = date_create($column['data_vistoria']);
                                @endphp
                                <tr>
                                    <td> {{ $count++ }}</td>
                                    <td>{{ $column['codigo'] }}</td>
                                    <td>{{ $column['predio'] }}</td>
                                    <td>{{ date_format($dt_vistoria, 'd/m/Y') }}</td>

                                </tr>
                                @php $qtdTotal++ @endphp
                              
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
