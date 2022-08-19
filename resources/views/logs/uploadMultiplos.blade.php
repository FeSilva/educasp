@extends('layouts.app', [
'class' => '',
'elementActive' => 'logs multiplos'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Logs </a></li>
            <li class="breadcrumb-item active" aria-current="page">Uploads Multiplos</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Logs de Uploads',
                'route' => '#',
                'id' => 'filterLog',
                'titleBtn' => 'Filtrar'
            ])
                @slot('body')
                    @component('components._table',['class' => 'logsUpload'])
                        @slot('thead')
                            <tr>
                                <th style="width: 300px;">Arquivo</th>
                                <th>Usúario</th>
                                <th>Data Envio</th>
                                <th>Status</th>
                            </tr>
                        @endslot
                        @slot('tbody')
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->arquivo.".pdf" }}</td>
                                    <td>{{$log->user[0]->name}}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($log->data_envio)) }}</td>
                                    <td>{{ $log->status }}</td>
                                </tr>
                            @endforeach
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent

    <!-- Modal -->
    @component('components._modal', [
        'id' => 'modalFilter',
        'title' => 'Filtrar Logs'
    ])
        @slot('body')
            @component('components._form', [
                'action' => route('logs.upload.list'),
                'method' => 'POST'
            ])
                @slot('slot')
                @csrf
                    <div class="row">
                        @component('components._input', [
                            'size' => '6',
                            'type' => 'date',
                            'label' => 'Data de:',
                            'name' => 'data_de',
                            'id' => 'date',
                            'attributes' => 'required'
                        ])
                        @endcomponent
                        @component('components._input', [
                            'size' => '6',
                            'type' => 'date',
                            'label' => 'Data até:',
                            'name' => 'data_ate',
                            'id' => 'date',
                            'attributes' => 'required'
                        ])
                        @endcomponent
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Filtrar</button>
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

@push('scripts')
    <script>
        $('#filterLog').click(function() {
            $('#modalFilter').modal('show');
        });
        var table = $('.logsUpload').DataTable();
        table.order([3,'asc']).draw();
    </script>
@endpush