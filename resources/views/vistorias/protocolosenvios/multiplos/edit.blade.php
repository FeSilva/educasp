@extends('layouts.app', [
'class' => '',
'elementActive' => 'protocolosMultiplos'
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
                        'action' => route('protocolosMultiplos.update-st'),
                        'method' => 'POST',
                        'attributes' => 'enctype=multipart/form-data accept=application/pdf'
                    ])
                        @slot('slot')
                            <div class="col-md-12 col-12 text-right">
                                <div class="col-md-12">
                                    <a href="/protocolos/multiplos/pdf/{{$aProtocolo['protocolo']['id']}}"
                                      ><img src="{{asset("paper")}}/img/icons/download.png"
                                            width="30px"></a>

                                    <a href="{{ route('protocolosMultiplos.update', $aProtocolo['protocolo']['id']) }}">
                                        <button class="btn btn-danger">Enviar</button>
                                    </a>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="id" value="{{$aProtocolo['protocolo']['id']}}">
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
                                        @if($aProtocolo['tipo_vistoria'] != 8)
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
                                                <td>{{$vistoria['name']}}</td>
                                                <td>{{$vistoria['tipo']}}</td>
                                                <td>{{$vistoria['dt_vistoria']}}</td>

                                            </tr>
                                        @endforeach
                                        </tbody>
                                        @else
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Tipo Vistoria</th>
                                                <th>Data</th>
                                                <th> <input type="checkbox" id="checkAll"></th>
                                            </tr>
                                            <tbody>
                                            @foreach($aProtocolo['vistorias'] as $vistoria)
                                                <input type="hidden" name="id_vistoria[]" value="{{$vistoria['id']}}">
                                                <tr>
                                                    <td>{{$vistoria['codigo']}}</td>
                                                    <td>
                                                        <a href="http://{{$_SERVER['HTTP_HOST']}}/storage/{{$vistoria['arquivo']}}" target="_blank">{{$vistoria['name']}}</a></td>
                                                    <td>{{$vistoria['tipo']}}</td>
                                                    <td>{{$vistoria['dt_vistoria']}}</td>
                                                    <td><input type='checkbox' name='check_{{$vistoria['id']}}' checked></td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        @endif
                                    </table>
                                </div>
                                @if($aProtocolo['tipo_vistoria'] == 8)
                                    @if($aProtocolo['protocolo']['merge'] < 1)

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <input type="file" name="protocolo_archive_st" id="protocolo_archive_st" class="btn form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary form-control">Gravar Aprovação</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
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
                        $('#protocolos').append('<tr>' +
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
