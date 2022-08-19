@extends('layouts.app', [
'class' => '',
'elementActive' => 'lista_envios'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lista de Envios</li>
        @endslot
    @endcomponent


    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Lista de Enviados',
                'route' => route('listaenvios.create')
            ])
                @slot('body')
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

