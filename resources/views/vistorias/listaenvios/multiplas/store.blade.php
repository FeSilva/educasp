@extends('layouts.app', [
'class' => '',
'elementActive' => 'lista_envios_multiplos'
])
<style>
    .loading{
        position: absolute;
    }
</style>
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
                        'action' => route('listaenviosMultiplos.enviaremail'),
                        'method' => 'POST',
                        'id' => 'formListaEnvios'
                    ])
                        @slot('slot')
                            <div class="col-md-12">
                                <input type="hidden" name="id" id="id" value="{{$id}}">
                                <div class="row">
                                    @component('components._input', [
                                        'size' => '6',
                                        'label' => 'Código:',
                                        'type' => 'text',
                                        'name' => 'codigo',
                                        'id' => 'codigo',
                                        'attributes' => 'readonly',
                                        'value' => $aListEnvio['codigo_lista']
                                    ])
                                    @endcomponent

                                    @component('components._input', [
                                        'size' => '6',
                                        'label' => 'Mês:',
                                        'type' => 'text',
                                        'name' => 'data',
                                        'id' => 'data',
                                        'attributes' => 'readonly',
                                        'value' =>  $aListEnvio['mes']
                                    ])
                                    @endcomponent
                                </div>
                                <div class="row">
                                    @component('components._input', [
                                     'size' => '6',
                                     'label' => 'Enviada por:',
                                     'type' => 'text',
                                     'name' => 'user_env',
                                     'id' => 'user_env',
                                     'attributes' => 'readonly',
                                     'value' => $aListEnvio['usuario']
                                 ])
                                    @endcomponent

                                    @component('components._input', [
                                        'size' => '6',
                                        'label' => 'Grupo:',
                                        'type' => 'text',
                                        'name' => 'group',
                                        'id' => 'group',
                                        'attributes' => 'readonly',
                                        'value' =>  ucfirst ($aListEnvio['grupo'])
                                    ])
                                    @endcomponent
                                </div>
                                <div class="row" id="buttons">
                                <!--<div class="col-md-6">
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
                                        </div>-->
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6" style="left:5px;"></div>
                                    <input type="hidden" name="CountMemory" id="countMemory"/>
                                    <span id="TotalMemorySize"></span>
                                    <span id="memorySize" style="margin-left:90%;"></span>
                                </div>
                                <div class="row">


                                    <table class="table">
                                        <tr>
                                            <th>Tipo Vistoria</th>
                                            <th>Fiscal Responsável</th>
                                            <th>Data Vistoria</th>
                                            <th>Status</th>

                                        </tr>
                                        <tbody id="listagem">

                                            @foreach($aListEnvio['vistorias'] as $vistoria)
                                                @php
                                                    $date=date_create($vistoria['dt_vistoria']);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{$vistoria['tipos_vistoria']}}
                                                    </td>
                                                    <td>
                                                        {!! $vistoria['fiscal'] !!}
                                                    <td>
                                                        {{date_format($date,'d/m/Y')}}
                                                    </td>
                                                    <td>
                                                        {{$vistoria['status']}}
                                                    </td>

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
            $("#listaEnvios").hide();
            $("#buttonEnviarEmail").hide();
            var idList = $("#id").val();


           /* $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'post',
                url: '{{route('listaenviosMultiplos.carregar')}}',
                data: {'idList':idList},
                success: function (data) {
                    $('#listaEnvios').show();
                    $('#listagem').html('');
                    $.each(data, function (index, value) {

                        if (index != 'totalSizeBytes' &&
                            index != 'totalSizeMb'
                        ) {
                            let dtVistoria = value.data.split('-').reverse().join("/");

                            $('#listagem').append('<tr>' +
                                `<input type='hidden' name='valueSizeKb_${value.id}' id='valueSizeKb_${value.id}' value='${value.size}' disabled>`+
                                "<td>" + value.tipo.name + '</td>' +
                                "<td>" + dtVistoria + '</td>' +
                                "<td>" + value.status + '</td>' +
                                `<td><input type='checkbox' id="checkbox_${value.id}" value='${value.id}' name='vistoria_ids[]' onchange='check(this.value)' disabled> </td>` +
                                '</tr>');
                        }
                    });

                    $("#listagem").show();
                    $("#TotalMemorySize").html("<strong>"+data.totalSizeMb+"</strong>");
                    $("#buttonEnviarEmail").show();
                    checkAllcheckbox();
                },
                beforeSend: function () {

                },
                error: function () {
                    alert('Erro ao gerar a listagem')
                }
            });*/
        });

        function checkAllcheckbox() {
            let checkboxs = $('input[type="checkbox"]');
            $.each(checkboxs, function (index, checkbox) {
                if($(checkbox).val() != 'on'){
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
            if($(checkbox).is(':checked')) {
                somaBytes(id)
            } else {
                subBytes(id)
            }
        }

        function subBytes(id, decimals = 2, type = 'sub') {
            var newByte = totalBytes(id, decimals, type);
            var bytes = parseInt($('#valueSizeKb_'+id).val());
            if (bytes === 0) return '0 Bytes';

            if(newByte[0] > 0 ){
                $("#countMemory").val(newByte[0]);
                $("#memorySize").html("<strong>"+newByte[0]+' '+newByte[1]+" para envio</strong>");
            }else{
                $("#countMemory").val(totalBytes(bytes, memorySize, 'sub'));
                $("#memorySize").html("<strong>"+ newByte[0] + ' '+newByte[1]+ "para envio</strong>");
            }
        }

        function somaBytes(id, decimals = 2){
            var bytes = parseInt($('#valueSizeKb_'+id).val());
            var newByte = totalBytes(id, decimals, 'soma');

            if (bytes === 0) return '0 Bytes';

            if(newByte[0] > 0 ){
                $("#countMemory").val(newByte[0]);
                $("#memorySize").html("<strong>"+newByte[0]+' '+newByte[1]+" para envio</strong>");
            }else{
                $("#countMemory").val(totalBytes(bytes, memorySize, 'soma'));
                $("#memorySize").html("<strong>"+ newByte[0] + ' '+newByte[1]+ "para envio</strong>");
            }
            //return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function totalBytes(id, decimals = 2, type) {
            var bytes = parseInt($('#valueSizeKb_'+id).val());
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

    </script>
@endpush
