@extends('layouts.app', [
'class' => '',
'elementActive' => 'protocolosMultiplos'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistorias</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastro de Protocolo de Envios</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Cadastro de Protocolo de Envios',
            ])
                @slot('body')
                    <span style="position: absolute;margin: 5px;top: 42px;"> Vistorias Disponiveis: {{$qtdVistorias}}</span>

                    @component('components._form', [
                        'action' => route('protocolosMultiplos.create'),
                        'method' => 'POST'
                    ])
                        @slot('slot')
                            <div class="col-md-12">
                                <div class="row">
                                    @component('components._select',[
                                        'size' => '4',
                                        'label' => 'Tipo Vistoria: ',
                                        'name' => 'tipo_vistoria',
                                        'id' => 'tipo_vistoria'
                                    ])
                                        @slot('options')
                                            <option>Selecione</option>
                                            @foreach($tipos as $tipo)
                                                <option value="{{$tipo->vistoria_tipo_id}}">{{$tipo->name}}</option>
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                    @component('components._input', [
                                        'size' => '4',
                                        'label' => 'Código:',
                                        'type' => 'text',
                                        'name' => 'codigo',
                                        'id' => 'codigo',
                                        'attributes' => 'readonly',
                                        'value' => $seq . '/' . date('Y')
                                    ])
                                    @endcomponent

                                    @component('components._input', [
                                        'size' => '4',
                                        'label' => 'Data:',
                                        'type' => 'date',
                                        'name' => 'data',
                                        'id' => 'data',
                                        'attributes' => 'readonly',
                                        'value' => date('Y-m-d')
                                    ])
                                    @endcomponent

                                </div>

                                <div class="row" id="buttons">
                                    <div class="col-md-6">
                                        @component('components.buttons._submit', [
                                            'title' => 'Gerar Lista',
                                            'type' => 'button',
                                            'attributes' => 'onclick=listarVistorias()'
                                        ])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6" style="left:5px;">
                                        <button type="submit" class="btn btn-primary btn-round"
                                                name="buttonGerarProtocolo" id="buttonGerarProtocolo" readonly="true">
                                            Gerar Protocolo
                                        </button>
                                    </div>

                                </div>
                                <div class="row" id="listaVistoria">


                                    <table class="table">
                                        <tr>
                                            <th>Tipo Vistoria</th>
                                            <th>Nome</th>
                                            <th>Data</th>
                                            <th>
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                        </tr>
                                        <tbody id="protocolosTable">
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
            $("#buttonGerarProtocolo").hide();
            $('#checkAll').change(function () {
                checkAllcheckbox();
            });
        });

        function checkAllcheckbox() {
            let checkboxs = $('input[type="checkbox"]');
            $.each(checkboxs, function (index, checkbox) {
                if ($(checkbox).is(':checked')) {
                    $(checkbox).removeAttr('checked');
                } else {
                    $(checkbox).attr('checked', 'checked');
                }
            })
        }

        function gerarProtocolos() {
            $("#formVistorias").submit(function (event) {
                event.preventDefault();
            });
        }

        function listarVistorias() {

            var dataDe = $("#dt_de").val();
            var dataAte = $("#dt_ate").val();
            var tipo_vistoria = $("#tipo_vistoria").val();
            if (tipo_vistoria == 'Selecione') {
                alert('Por favor selecione um tipo de vistoria para gerar Protocolo.');
            } else {

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    type: 'post',
                    url: '{{route('carrega.vistoriasMultiplas')}}',
                    data: {tipoVistoria:tipo_vistoria},
                    success: function (data) {

                        if (data.length > 0) {
                            $('#protocolosTable').html('');
                            $.each(data, function (index, value) {

                                $('#protocolosTable').append('<tr>' +
                                    "<input type='hidden' name='id_vistoria[]' value='" + value.id + "'>" +
                                    "<td>" + value.tipos.name + '</td>' +
                                    "<td>" + value.predio.name + '</td>' +
                                    "<td>" + value.dt_vistoria + "</td>" +
                                    "<td><input type='checkbox' name='check_" + value.id + "' checked></td>" +
                                    '</tr>');
                            });

                            $("#listaVistoria").show(2);
                            $("#buttonGerarProtocolo").show();
                        } else {
                            alert('Não existem vistorias a serem geradas.');
                        }
                    },
                    beforeSend: function () {

                    },
                    error: function () {
                        alert('Erro ao listar vistorias')
                    }
                });
            }
        }
    </script>
@endpush
