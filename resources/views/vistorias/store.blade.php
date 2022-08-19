@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])


@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistoria</a></li>
            <li class="breadcrumb-item active"
                aria-current="page">{{ empty($pi['codigo']) ? 'Cadastro de Vistoria' : 'Editar Cadastro de Vistoria' }}</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Cadastro de Vistorias',
                'route' => '#',
                'id' => 'infos',
                'titleBtn' => 'Ver Infos'
            ])

                @slot('body')
                    @component('components._form',[
                        'method' => 'POST',
                        'action' => route('vistorias.create'),
                        'attributes' => 'enctype=multipart/form-data accept=application/pdf'
                    ])
                        @slot('slot')
                            <div class="col-md-12">
                                <div class="row">
                                    @component('components._input', [
                                        'size' => '2',
                                        'type' => 'text',
                                        'label' => 'Código PI:',
                                        'name' => 'codigo_pi',
                                        'id' => 'codigo_pi',
                                        'value' => old('codigo_pi')
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                            <div id="tipoVistoria" style="display:none;">
                                <div class="col-md-12">
                                    <div class="row">
                                        @component('components._input', [
                                            'size' => '6',
                                            'type' => 'text',
                                            'label' => 'Nome Prédio:',
                                            'name' => 'name_predio',
                                            'id' => 'name_predio',
                                            'class' => 'input-escondido',
                                            'attributes' => 'readonly',
                                            'value' => old('name_predio')
                                        ])
                                        @endcomponent

                                        @component('components._input', [
                                            'size' => '6',
                                            'type' => 'text',
                                            'label' => 'Diretoria:',
                                            'name' => 'diretoria',
                                            'id' => 'diretoria',
                                            'class' => 'input-escondido',
                                            'attributes' => 'readonly',
                                            'value' => old('diretoria')
                                        ])
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        @component('components._select',[
                                            'size' => '3',
                                            'label' => 'Tipo Vistoria: ',
                                            'name' => 'tipo_vistoria',
                                            'id' => 'tipo_vistoria'
                                        ])
                                            @slot('options')

                                            @endslot
                                        @endcomponent
                                    </div>
                                </div>
                            </div>


                            <div id="aberturaRow" style="display:none">
                                <div class="col-md-12">
                                    <div class="row">
                                        @component('components._input',[
                                            'size' => '6',
                                            'type' => 'date',
                                            'label' => 'Data de Abertura: ',
                                            'name' => 'data_abertura',
                                            'id' => 'data_abertura',
                                            'value' => old('data_abertura')
                                        ])
                                        @endcomponent

                                        {{--@component('components._input',[
                                            'size' => '6',
                                            'type' => 'file',
                                            'label' => 'Arquivo: ',
                                            'name' => 'arquivo_folha',
                                            'id' => 'arquivo_folha',
                                            'value' => old('arquivo_folha')
                                        ])
                                        @endcomponent--}}
                                    </div>
                                </div>
                            </div>
                            <div id="trasnferenciaRow" style="display:none;">
                                <div class="col-md-12">
                                    <div class="row">

                                        @component('components._input',[
                                          'size' => '4',
                                          'type' => 'date',
                                          'label' => 'Data do LO: ',
                                          'name' => 'data_lo',
                                          'id' => 'data_lo',
                                          'onblur'=>'validarData()',
                                          'value' => old('data_lo')
                                      ])
                                        @endcomponent
                                        {{--@component('components._input',[
                                            'size' => '3',
                                            'type' => 'file',
                                            'label' => 'Arquivo do LO: ',
                                            'name' => 'arquivo_lo',
                                            'id' => 'arquivo_lo',
                                            'value' => old('arquivo_lo')
                                        ])
                                        @endcomponent--}}
                                        @component('components._input',[
                                            'size' => '4',
                                            'type' => 'number',
                                            'label' => 'Nº Funcionarios:',
                                            'name' => 'funcionario',
                                            'id' => 'funcionario',
                                            'value' => old('funcionario')
                                        ])
                                        @endcomponent
                                        @component('components._input',[
                                        'size' => '4',
                                        'type' => 'text',
                                        'label' => '%Atual Global',
                                        'name' => 'media_global',
                                        'id' => 'media_global',
                                        'attributes' => 'maxlength=6',
                                        'attributes' => "onblur=valideGlobal(this.value)",
                                        'value' => old('media_global')
                                        ])
                                        @endcomponent
                                    </div>

                                    <div class="row">
                                        @component('components._select',[
                                                'size' => '4',
                                                'label' => 'Andamento: ',
                                                'name' => 'andamento',
                                                'id' => 'andamento'
                                            ])
                                            @slot('options')
                                                <option selected disabled>Selecione</option>
                                                @foreach($vistoriaAndamentos as $andamento)
                                                    <option
                                                        value="{{ $andamento->andamento_id }}" {{ (old('andamento') == $andamento->andamento_id) ? 'selected' : '' }}>{{ $andamento->name }}</option>
                                                @endforeach
                                            @endslot
                                        @endcomponent

                                        @component('components._select',[
                                                'size' => '4',
                                                'label' => 'Ritmo: ',
                                                'name' => 'ritmo',
                                                'id' => 'ritmo'
                                            ])
                                            @slot('options')
                                                <option selected disabled>Selecione</option>
                                                @foreach($vistoriaRitmos as $ritmo)
                                                    <option
                                                        value="{{ $ritmo->ritmo_id }}" {{ (old('ritmo') == $ritmo->ritmo_id) ? 'selected' : '' }}>{{ $ritmo->name }}</option>
                                                @endforeach
                                            @endslot
                                        @endcomponent

                                        @component('components._input',[
                                           'size' => '4',
                                           'type' => 'date',
                                           'label' => 'Previsão Término',
                                           'name' => 'prev_termino',
                                           'id' => 'prev_termino',
                                           'value' => old('prev_termino')
                                       ])
                                        @endcomponent
                                    </div>

                                    <div class="row">
                                        <div class="col-8">
                                            <h3 class="mb-0">Itens Vistorias</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table ultimasVistorias" id="table">
                                                <thead class=" text-primary">
                                                <th>
                                                    Nº Item
                                                </th>
                                                <th>
                                                    Tipo
                                                </th>
                                                <th>
                                                    Prazo
                                                </th>
                                                <th>
                                                    Valor
                                                </th>
                                                <th>
                                                    Dt
                                                </th>
                                                <th>
                                                    %
                                                </th>
                                                <th>
                                                    %Atual
                                                </th>
                                                </thead>
                                                <tbody id="itens">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="submite" style="display:none;">
                                    @component('components.buttons._submit')@endcomponent
                                </div>
                            </div>
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
            @component('components._card', [
                'title' => 'Ultimas Vistorias Cadastradas',
                'cardId' => 'ultimasVistorias'
            ])
                @slot('body')
                    @component('components._table')
                            @slot('thead')
                                <tr>
                                    <th>Código</th>
                                    <th>Data de Vistoria</th>
                                    <th>Status</th>
                                    <th>Tipo</th>
                                    <th>% Global</th>
                                </tr>
                            @endslot
                            @slot('tbody')

                            @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
    <!-- Modal -->
    @component('components._modal', [
        'title' => "",
        'id' => 'modalInfos'
    ])
        @slot('body')
            @component('components._form', [
                'action' => '#',
                'method' => 'GET'
            ])
                @slot('slot')
                    <div class="row">
                        @component('components._input', [
                            'size' => '12',
                            'type' => 'text',
                            'label' => 'Nome Prédio:',
                            'name' => 'name_predio_modal',
                            'id' => 'name_predio_modal',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('name_predio')
                        ])
                        @endcomponent
                    </div>
                    <div class="row">
                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Código do Prédio:',
                            'name' => 'codigo_predio',
                            'id' => 'codigo_predio',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('codigo_predio')
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Diretoria:',
                            'name' => 'diretoria_modal',
                            'id' => 'diretoria_modal',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('diretoria')
                        ])
                        @endcomponent
                        @component('components._input', [
                            'size' => '3',
                            'type' => 'date',
                            'label' => 'Assinatura:',
                            'name' => 'assinatura',
                            'id' => 'assinatura',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('assinatura')
                        ])
                        @endcomponent
                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Valor Total:',
                            'name' => 'valor_total',
                            'id' => 'valor_total',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('valor_total')
                        ])
                        @endcomponent

                    </div>
                    <div class="row">
                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Prazo Total:',
                            'name' => 'prazo_total',
                            'id' => 'prazo_total',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('prazo_total')
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Fiscal:',
                            'name' => 'fiscal',
                            'id' => 'fiscal',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('fiscal')
                        ])
                        @endcomponent
                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Empresa Contratada:',
                            'name' => 'empresa_contratada',
                            'id' => 'empresa_contratada',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('empresa_contratada')
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Objeto do PI:',
                            'name' => 'objeto_pi',
                            'id' => 'objeto_pi',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => old('objeto_pi')
                        ])
                        @endcomponent
                    </div>
                @endslot
            @endcomponent
        @endslot
    @endcomponent
    <!-- End Modal -->
@endsection

@push('scripts')
    <script type="text/javascript">

        $(document).ready(function () {
            $('#infos').hide();
            $('#ultimasVistorias').hide();
            //MASK
            $('#codigo_pi').mask('0000/00000');
            $('#codigo_predio').mask('00.00.000');
            $('#telefone').mask('(00) 00000-0000');
            $('#media_global').mask('##0,00', {reverse: true});
            $('#valor_total').mask('#.##0,00', {
                reverse: true
            });
        });



        $("#codigo_pi").change(function () {
            //function carrega predio.
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "/carrega/pi",
                data: 'codigoPi=' + $(this).val(),
                success: function (data) {
                    console.log(data);
                    $('#ultimasVistorias').show();
                    $('#infos').click(function () {
                        $('#modalInfos').modal('show');
                    })
                    var json = $.parseJSON(data);
                    if (json.error) {
                        alert(json.error);
                    }
                    json.vistorias.sort().reverse();
                    loadLastestVistorias(json.vistorias);
                    $("#codigo").val(json.codigo);
                    document.querySelector('#modalInfos #exampleModalLabel').textContent = `Infos - ${json.codigo}`
                    $("#name_predio").val(json.predios[0].name);
                    $("#name_predio_modal").val(json.predios[0].name);
                    $('#assinatura').val(json.dt_assinatura);
                    $('#valor_total').val(json.valor_total);
                    $('#fiscal').val(json.user[0].name);
                    $("#empresa_contratada").val(json.empreiteiras[0].name);
                    $('#codigo_predio').val(json.predios[0].codigo)
                    $("#diretoria").val(json.predios[0].diretoria);
                    $("#diretoria_modal").val(json.predios[0].diretoria);
                    $("#prazo_total").val(json.prazo_total);
                    $("#objeto_pi").val(json.objeto_pi);
                    if (json.tipos) {
                        $("#tipo_vistoria").html("<option selected disabled>Selecione</option>");
                        $.each(json.tipos, function (key, value) {
                            $("#tipo_vistoria").append("<option value=" + key + ">" + value + "</option>");
                        });
                    }
                    if (json.items.length > 0) {
                        $("#itens").html('');
                        $.each(json.items, function (key, value) {
                            if (value.andamento_items) {
                                var progress = value.andamento_items.progress;
                            } else {
                                var progress = '';
                            }

                            if (value.dt_abertura) {
                                var abertura = value.dt_abertura.replace(/(\d*)-(\d*)-(\d*).*/, '$3-$2-$1');
                            } else {
                                var abertura = '';
                            }
                            var maxValue = '100,00';
                            var msgValue = 'O valor não pode ser maior que 100,00%';
                            $("#itens").append(
                                "<tr>" +
                                "<input type='hidden' name='progressLast_" + value.id + "' id='progressLast_" + value.id + "' value='" + progress + "'>" +
                                "<td>" + value.num_item + "</td>" +
                                "<td>" + value.tipo_item + "</td>" +
                                "<td>" + value.prazo + "</td>" +
                                "<td>" + value.valor + "</td>" +
                                "<td>" + abertura + "</td>" +
                                "<td>" + progress + "</td>" +
                                "<td><input type='text' class='form-control porcentagemAtual' name='item_" + value.id + "' id='item_" + value.id + "'maxlength='6' onblur=valideValue("+value.id+")></td>" +
                                "</tr>");
                            let itemId = value.id;

                        });
                    } else {
                        $("#itens").html('');
                    }

                    $("#tipoVistoria").show();
                    $('#infos').show();
                    $('.porcentagemAtual').mask('##0,00', {reverse: true});

                },
                error: function () {
                    alert('Por favor informe um código de processo de intervenção valido !');
                }
            });
        });

        function valideGlobal(data){
            var value = data;

            if(parseFloat(value.replace(',','.')) > 100.00){
                alert('Valor não pode ser maior que 100,00%');
                $("#media_global").val('').focus();
            }
        }

        function valideValue(data){
            var value = $("#item_"+data).val();
            var lastValue = $("#progressLast_"+data).val()
            if(value.replace(',','.') > 100.00){
                alert('Valor não pode ser maior que 100,00%');
                $("#item_"+data).val('').focus();
                return;
            }

            if(parseFloat(lastValue)  >  parseFloat(value.replace(",","."))){
                alert('Valor não pode ser menor que o anterior');
                $("#item_"+data).val('').focus();
                return;
            }

        }

        $("#tipo_vistoria").change(function () {
            var tipoVistoria = $(this).val();

            //Refatorar regra de visualização quando cadastro estiver finalizado
            if (tipoVistoria == 1) {
                $("#trasnferenciaRow").hide();
                $("#aberturaRow").show();
                $("#submite").show();
            }
            if (tipoVistoria == 2) {
                $("#aberturaRow").hide();
                $("#trasnferenciaRow").show();
                $("#submite").show();
            }
            if (tipoVistoria == 3) {
                $("#aberturaRow").hide();
                $("#trasnferenciaRow").show();
                $("#submite").show();
            }

        });

        function openFieldsIfHasErrors(typeError) {
            switch (typeError) {
                case '1':
                    $("#tipoVistoria").show();
                    $("#aberturaRow").show();
                    $("#submite").show();
                    $("#codigo_pi").change();
                    break;
                case '2':
                    $("#tipoVistoria").show();
                    $("#trasnferenciaRow").show();
                    $("#submite").show();
                    $("#codigo_pi").change();
                    break;
                case '3':
                    $("#tipoVistoria").show();
                    $("#trasnferenciaRow").show();
                    $("#submite").show();
                    $("#codigo_pi").change();
                    break;
            }
        }


        function loadLastestVistorias(vistorias) {

            let ultimasVistoriasTable = document.querySelector('#ultimasVistorias table tbody');
            ultimasVistoriasTable.innerHTML = '';
            vistorias.forEach(vistoria => {
                ultimasVistoriasTable.insertAdjacentHTML('beforeend', `
                    <tr>
                        <input type=hidden name=dt_ult_vistoria id=dt_ult_vistoria value=${vistoria.dt_vistoria}>
                        <td>${vistoria.codigo}</td>
                        <td>${dateFormat(vistoria.dt_vistoria)}</td>
                        <td>${vistoria.status}</td>
                           <td>${vistoria.tipos.name}</td>
                           <td>${vistoria.avanco_fisico == null ? 0 : vistoria.avanco_fisico }</td>
                    </tr>
                `);
            });
        }

        function dateFormat(date) {
            return date.split('-').reverse().join('/');
        }


        $("#data_lo").on('blur',function(){
                var dt_now = $(this).val();
                var dt_last = $('#dt_ult_vistoria').val();

             $.ajax({
                 headers: {
                     'X-CSRF-Token': $('input[name="_token"]').val()
                 },
                 type: 'POST',
                 url: "/vistorias/validateDate",
                 data: {dt_now:dt_now,dt_last:dt_last},
                 success: function (data) {
                     console.log(data);
                    if(data !== 'sucesso'){
                        alert(data);
                    }
                 },
                 error: function(error){
                    alert(error);
                 }
             });

        });

    </script>
    @if(session()->has('tipo_vistoria')))
    <script>
        openFieldsIfHasErrors("{{ session()->get('tipo_vistoria') }}")
    </script>
    @endif
@endpush
