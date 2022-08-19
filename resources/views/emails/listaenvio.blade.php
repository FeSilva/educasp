<html>
<style>
    td{
        margin:10px;
    }
</style>
<body>

@if(isset($vistoriasEnviadas) and $vistoriaEnviadas == true)
<p>Seguem as vistorias referentes à Lista {{$seq}} encaminhadas  anteriormente <span style='background-color:yellow;'>(assinadas)</span> por conta da antecipação do ano fiscal.</p>
@else
<p>Seguem as vistorias referentes à Lista {{$seq}} do Consórcio Educa SP</p>
@endif
<table>
    <thead>
    <tr>
        <th>PI</th>
        <th>Data Vistoria</th>
        <th>Arquivo</th>

    </tr>
    </thead>
    <tbody>
    @foreach($listaEnviada as $lista)
        @php
        $nameArquivo = explode("/",$lista->arquivo);
        $date=date_create($lista->data_envio);
        $data = date_format($date,"d/m/Y");
        @endphp
        <tr>
            <td>
                {{$lista->codigo_lista}}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
            <td>{{$data}}</td>
            <td>{{$nameArquivo[2]}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p>Consórcio Educa São Paulo.</p>
</body>
</html>
