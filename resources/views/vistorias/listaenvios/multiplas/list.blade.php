@extends('layouts.app', [
'class' => '',
'elementActive' => 'lista_envios_multiplos'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lista de Envios</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Lista de Enviados',
                'route' => route('listaenviosMultiplos.create')
            ])
                @slot('body')
                    <span style="position: absolute;margin: 5px;top: 42px;"> Vistorias Disponiveis: {{$qtdAprovadas}}</span>
                    @component('components._table')
                        @slot('thead')
                            <tr>
                                <th>Código</th>
                                <th>Data de Envio</th>
                                <th  align="center">Qtd Vistorias</th>
                                <th>Status</th>
                                <th>
                                    Ações
                                </th>
                            </tr>
                        @endslot
                        @slot('tbody')
                            @foreach($enviados as $enviado)
                                @php
                                    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                                    $date = date("F", mktime(0, 0, 0, $enviado->mes, 10));
                                    $mes = ucfirst( utf8_encode( strftime("%B", strtotime($date))));
                                @endphp
                                <tr>
                                    <td>{{ $enviado->codigo_lista }}</td>
                                    <td>{{ $mes  }}</td>
                                    <td>{{$enviado->qtd_vistoria}}</td>
                                    <td>{{ $enviado->status }}</td>
                                    <td><button type='button' class='btn btn-danger' onclick="window.location='{{ route('listaenviosMultiplos.store', ['id' => $enviado->id]) }}'">Visualizar Lista</button></td>
                                </tr>
                            @endforeach
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
