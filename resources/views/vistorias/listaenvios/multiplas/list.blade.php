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
                                <th>Tipo de Vistoria</th>
                                <th  align="center">Qtd Vistorias</th>
                                <th>Enviado por</th>
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
                                    <td>{!! $enviado->tipo !!}</td>
                                    <td>{{$enviado->qtd_vistoria}}</td>
                                    <td>{!! $enviado->enviado_por !!}</td>
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

@push('scripts')
    <script type="text/javascript">
        $('#table').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                },
                "order": [[ 1, "asc" ]]
            }
        });

    </script>
@endpush
