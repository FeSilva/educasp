@extends('layouts.app', [
'class' => '',
'elementActive' => 'protocolos'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualizar Protocolo de Envio</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')

            @component('components._card', [
                'title' => 'Cadastro de Protocolo de Envios',
                
            ])
                @slot('body')
                    @component('components._form', [
                        'action' => route('protocolos.create'),
                        'method' => 'POST'
                    ])
                        @slot('slot')
                            <div class="col-md-12">
                                <div class="row">
                                    @component('components._input', [
                                        'size' => '6',
                                        'label' => 'Código:',
                                        'type' => 'text',
                                        'name' => 'codigo',
                                        'id' => 'codigo',
                                        'attributes' => 'readonly',
                                        'value' => old('codigo',$aProtocolo['protocolo']['cod_protocolo'])
                                    ])
                                    @endcomponent

                                    @component('components._input', [
                                        'size' => '6',
                                        'label' => 'Data:',
                                        'type' => 'date',
                                        'name' => 'data',
                                        'id' => 'data',
                                        'attributes' => 'readonly',
                                        'value' => old('data',$aProtocolo['protocolo']['data_protocolo'])
                                    ])
                                    @endcomponent
                                </div>
                                <div class="row">
                                    <table class="table">
                                        <tr>
                                            <th>#</th>
                                            <th>Nome</th>
                                            <th>Tipo Vistoria</th>
                                            <th>Data</th>
                                        </tr>
                                        <tbody>
                                            @foreach($aProtocolo['vistorias'] as $vistoria)
                                                <tr>
                                                    <td>{{$vistoria['codigo']}}</td>
                                                    <td>{{$vistoria['predio']}}</td>
                                                    <td>{{$vistoria['tipo']}}</td>
                                                    <td>{{$vistoria['dt_vistoria']}}</td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent

        @endslot
    @endcomponent
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#listaVistoria").hide();
        });

        function gerarProtocolos() {
            alert('test');
            $("#formVistorias").submit(function (event) {

                event.preventDefault();
            });

        }

        function listarVistorias() {


            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'post',
                url: '{{route('carrega.vistorias')}}',
                data: {},
                success: function (data) {
                    $("#listaVistoria").show(2);
                    $.each(data, function (index, value) {
                        $('#protocolosTable').append('<tr>' +
                            "<input type='hidden' name='id_vistoria[]' value='" + value.id + "'>" +
                            "<td>" + value.codigo + '<t/d>' +
                            "<td>" + value.pi.predios[0].name + '</td>' +
                            "<td>" + value.tipos.name + '</td>' +
                            "<td>" + value.dt_vistoria + "</td>" +
                            "<td><input type='checkbox' name='check_'" + value.id + "'></td>" +
                            '</tr>');
                    });
                },
                beforeSend: function () {

                },
                error: function () {
                    alert('Erro ao listar vistorias')
                }
            });
        }
    </script>
@endpush
