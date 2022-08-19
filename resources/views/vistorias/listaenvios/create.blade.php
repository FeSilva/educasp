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

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Lista de Envios',
            ])
                @slot('body')

                    @component('components._form', [
                        'action' => route('listaenvios.enviaremail'),
                        'method' => 'POST',
                        'id' => 'formListaEnvios'
                    ])
                        @slot('slot')
                            <span style="position: absolute;margin: 5px;top: 42px;"> Vistorias Disponiveis: {{$qtdAprovadas}}</span>
                            <div class="float-right" style="position: relative; top: -50px">
                                <input type="checkbox" name="vistorias_enviadas"> Vistorias Enviadas
                            </div>
                            <div class="col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <label class="col-md- col-form-label">Mês:</label>
                                        <div class="form-group">
                                            <select class="form-control" name="mes" id="mes" onChange="consultaListaMes(this.value)">
                                                <option>Escolha o mês</option>
                                                <option value="01">Janeiro</option>
                                                <option value="02">Fevereiro</option>
                                                <option value="03">Março</option>
                                                <option value="04">Abril</option>
                                                <option value="05">Maio</option>
                                                <option value="06">Junho</option>
                                                <option value="07">Julho</option>
                                                <option value="08">Agosto</option>
                                                <option value="09">Setembro</option>
                                                <option value="10">Outubro</option>
                                                <option value="11">Novembro</option>
                                                <option value="12">Dezembro</option>
                                            </select>
                                        </div>
                                    </div>
                                    @component('components._input', [
                                                                        'size' => '6',
                                                                        'label' => 'Código:',
                                                                        'type' => 'text',
                                                                        'name' => 'codigo',
                                                                        'id' => 'codigo'
                                                                    ])
                                    @endcomponent
                                </div>
                                <small>
                                    <center>Código sequência pode ser alterado pelo usuário, caso a lista escolhida já
                                        exista o sistema irá informar.
                                        <center>
                                </small>

                                <div class="row" id="buttons">
                                    <div class="col-md-6">
                                        @component('components.buttons._submit', [
                                            'title' => 'Gerar Lista',
                                            'type' => 'button',
                                            'attributes' => 'onclick=listarEnvios()'
                                        ])
                                        @endcomponent
                                    </div>
                                    <div class="col-md-6" style="left:5px;">
                                        <button type="submit" class="btn btn-primary btn-round"
                                                name="buttonEnviarEmail" id="buttonEnviarEmail" readonly="true">
                                            Enviar Email
                                        </button>
                                    </div>
                                    <input type="hidden" name="CountMemory" id="countMemory"/>
                                    <span id="TotalMemorySize"></span>
                                    <span id="memorySize" style="margin-left:90%;"></span>
                                </div>
                                <div class="row" id="listaEnvios">


                                    <table class="table">
                                        <tr>
                                            <th>Código</th>
                                            <th>Data Vistoria</th>
                                            <th>Status</th>
                                            <th><input type="checkbox" id="checkAll" onclick="checkAllcheckbox()"></th>
                                        </tr>
                                        <tbody id="listagem">
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

<div class="modal" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
    <div class="overlay"
         style="position: absolute; top: 0;bottom: 0;left: 0;right: 0;height: 100%;width: 100%;opacity:0.6;transition: .5s ease; background-color: #000;"></div>
    <div class="modal-dialog large">
            <div class="modal-body">
                <div class="responsive-banner">
                    <img src="{{ asset('paper') }}/img/loading.gif">
                </div>
            </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#listaEnvios").hide();


            $("#buttonEnviarEmail").hide();

            $("#buttonEnviarEmail").click(function() {
                $('#largeModal').show();

            });
        });

        function  consultaListaMes(mes){
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'post',
                url: '{{route('listaenvios.consultaMes')}}',
                data: {'mes':mes},
                success: function (data) {
                    $("#codigo").val(data)
                },
                beforeSend: function () {
                    $("#codigo").val()
                },
                error: function () {
                    alert('Erro ao gerar a listagem')
                }
            });
        }
        function checkAllcheckbox() {
            let checkboxs = $('input[type="checkbox"]');
            $.each(checkboxs, function (index, checkbox) {
                if ($(checkbox).val() != 'on') {
                    somaBytes($(checkbox).val());
                }

                if ($(checkbox).is(':checked')) {
                    $(checkbox).removeAttr('checked');
                    subBytes($(checkbox).val(), 2, 'subTotal');

                } else {
                    $(checkbox).attr('checked', 'checked');
                }
            })
        }


        function check(id) {
            let checkbox = $(`#checkbox_${id}`);
            if ($(checkbox).is(':checked')) {
                somaBytes(id)
            } else {
                subBytes(id)
            }
        }

        function subBytes(id, decimals = 2, type = 'sub') {
            var newByte = totalBytes(id, decimals, type);
            var bytes = parseInt($('#valueSizeKb_' + id).val());
            if (bytes === 0) return '0 Bytes';

            if (newByte[0] > 0) {
                $("#countMemory").val(newByte[0]);
                $("#memorySize").html("<strong>" + newByte[0] + ' ' + newByte[1] + " para envio</strong>");
            } else {
                $("#countMemory").val(totalBytes(bytes, memorySize, 'sub'));
                $("#memorySize").html("<strong>" + newByte[0] + ' ' + newByte[1] + "para envio</strong>");
            }
        }

        function somaBytes(id, decimals = 2) {
            var bytes = parseInt($('#valueSizeKb_' + id).val());
            var newByte = totalBytes(id, decimals, 'soma');

            if (bytes === 0) return '0 Bytes';

            if (newByte[0] > 0) {
                $("#countMemory").val(newByte[0]);
                $("#memorySize").html("<strong>" + newByte[0] + ' ' + newByte[1] + " para envio</strong>");
            } else {
                $("#countMemory").val(totalBytes(bytes, memorySize, 'soma'));
                $("#memorySize").html("<strong>" + newByte[0] + ' ' + newByte[1] + "para envio</strong>");
            }
            //return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function totalBytes(id, decimals = 2, type) {
            var bytes = parseInt($('#valueSizeKb_' + id).val());
            var memorySize = parseInt($("#countMemory").val());
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));
            if (isNaN(memorySize)) {
                memorySize = 0;
            }
            if (type == 'subTotal') {
                return [
                    0,
                    'KB'
                ]
            }
            if (type == 'soma') {
                return [
                    parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + parseInt(memorySize),
                    sizes[i]
                ];
            } else {

                return [
                    parseInt(memorySize) - parseFloat((bytes / Math.pow(k, i)).toFixed(dm)),
                    sizes[i]
                ];
            }
        }

        function listarEnvios() {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'post',
                url: '{{route('listaenvios.carregar')}}',
                data: {},
                success: function (data) {

                    $('#listagem').html('');

                    if (data[0]) {
                        $('#listaEnvios').show();
                        $.each(data, function (index, value) {

                            if (index != 'totalSizeBytes' &&
                                index != 'totalSizeMb'
                            ) {

                                let dtVistoria = value.data.split('-').reverse().join("/");

                                $('#listagem').append('<tr>' +
                                    `<input type='hidden' name='valueSizeKb_${value.id}' id='valueSizeKb_${value.id}' value='${value.size}'>` +
                                    "<td>" + value.codigo + '</td>' +
                                    "<td>" + dtVistoria + '</td>' +
                                    "<td>" + value.status + '</td>' +
                                    `<td><input type='checkbox' id="checkbox_${value.id}" value='${value.id}' name='vistoria_ids[]' onchange='check(this.value)'> </td>` +
                                    '</tr>');
                            }
                        });

                        $("#listagem").show();
                        $("#TotalMemorySize").html("<strong>" + data.totalSizeMb + "</strong>");
                        $("#buttonEnviarEmail").show();
                        $('#largeModal').hide();
                        checkAllcheckbox();
                    } else {
                        $('#largeModal').hide();
                        alert('Não existem vistorias para serem enviadas.');
                    }
                },
                beforeSend: function () {
                    $('#largeModal').show('3');
                },
                error: function () {
                    alert('Erro ao gerar a listagem')
                }
            });
        }
    </script>
@endpush
