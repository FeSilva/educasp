<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Educa SP - Protoclo: {{$aProtocolo['protocolo']['codigo']}} </title>
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
    </style>
</head>
<body>
{{ dd(storage_path('app/public/Logo_fde.jpg')) }}
<table border="0">
    <tbody>
        <tr>
            <td><img src="{{storage_path('app/public/Logo_fde.jpg')}}" class="img-logo"></td>

            <td><img src="{{storage_path('app/public/Logo_fde.jpg')}}" class="img-logo" style="margin-left: 15px;"></td>

        </tr>
    </tbody>
</table>
<table border="1" style="
            padding: 0.5rem;
            text-align: center;
        ">
    <thead>
    <tr>
        <th>PI</th>
        <th>Código</th>
        <th>Data vistoria</th>
        <th colspan="2">Nome</th>


    </tr>
    </thead>
    <tbody>
    @forelse($aProtocolo['vistorias'] as $vistoria)
        <tr>
            <td>{{$vistoria['codigo']}}</td>
            <td>{{$vistoria['codigo_predio']}}</td>
            <td>{{$vistoria['dt_vistoria']}}</td>
            <td  colspan="2">{{$vistoria['predio']}}</td>
        </tr>
    @empty
        <tr><td colspan="5">Nenhum resultado</td></tr>
    @endforelse

    </tbody>
</table>




<p>As vistorias acima elencadas/descritas foram analisadas e devidamente validadas pela supervisão de obras. Segue para aprovação :</p><br /><br />
<p>{{$assinatura = "______________________________________"}}<br />
GERENCIA DE OBRAS RESPONSÁVEL <br>AFFONSO COAN FILHO</p>
</body>
</html>
