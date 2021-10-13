<html>
<style>
    td{
        margin:10px;
    }
</style>
<body>

<p>Seguem as vistorias referentes à Lista {{$seq}} do Consórcio Educa SP</p>
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
