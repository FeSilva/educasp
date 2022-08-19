@extends('layouts.app', [
'class' => '',
'elementActive' => 'protocolosMultiplos'
])


@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Protocolo de Envios</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Protocolo de Envios',
                'route' => route('protocolosMultiplos.store')
            ])

                @slot('body')
                    <span style="position: absolute;margin: 5px;top: 42px;"> Vistorias Disponiveis: {{$qtdVistorias}}</span>
                    @component('components._table')
                        @slot('thead')
                            <tr>
                                <th align="center">Código</th>
                                <th align="center">data</th>
                                <th align="center">total de vistorias</th>
                                <th align="center">Tipo de Protoclo</th>
                                <th align="center">Status</th>
                                <th align="center">Ações</th>
                            </tr>
                        @endslot
                        @slot('tbody')
                            @if(!empty($aProtocolos))
                                @foreach($aProtocolos as $protocolo)
                                    @php
                                        $path = preg_replace('/\//i', '_',$protocolo['codigo']);
                                    @endphp
                                    <tr>
                                        <td>{{ $protocolo['codigo'] }}</td>
                                        <td>{{ $protocolo['data'] }}</td>
                                        <td align="center">{{$protocolo['total_vistoria']}}</td>
                                        <td>{{$protocolo['tipo_protocolo']}}</td>
                                        <td>{{$protocolo['status'] == 0 ? 'Protocolo Gerado' : 'Protocolo Enviado'}}</td>
                                        <td>
                                            <a href="/documents/protocolos/multiplos/edit/{{$protocolo['id']}}">
                                                <img src="{{asset("paper")}}/img/icons/view.png"
                                                    width="30px">
                                            </a>

                                            <a href="{{ route('protocolosMultiplos.delete', $protocolo['id']) }}">
                                                <img type='submit' src="{{asset("paper")}}/img/icons/excluir.png"
                                                    width="20px">
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" align="center">
                                        Nenhum registro encontrado.
                                    </td>
                                </tr>
                            @endif
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
