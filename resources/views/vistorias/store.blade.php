@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
]);


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
            @component('components._card')
                @slot('title')
                    Cadastro de Vistoria
                @endslot
                @slot('body')
                    @component('components._form',[
                        'method' => 'POST',
                        'action' => '#',
                        'attributes' => 'enctype=multipart/form-data'
                    ])
                        @slot('slot')
                            <div class="row">
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Código PI:',
                                    'name' => 'codigo',
                                    'id' => 'codigo'
                                ])
                                @endcomponent
                            </div>
                            <div class="row">
                                @component('components._input', [
                                    'size' => '6',
                                    'type' => 'text',
                                    'label' => 'Nome Prédio:',
                                    'name' => 'predio',
                                    'id' => 'predio',
                                    'class' => 'input-escondido',
                                    'attributes' => 'readonly'
                                ])
                                @endcomponent

                                @component('components._input', [
                                    'size' => '6',
                                    'type' => 'text',
                                    'label' => 'Diretoria:',
                                    'name' => 'diretoria',
                                    'id' => 'diretoria',
                                    'class' => 'input-escondido',
                                    'attributes' => 'readonly'
                                ])
                                @endcomponent
                            </div>

                            <div class="row" id="tipoVistoria" style="display:none;">
                                @component('components._select',[
                                    'size' => '12',
                                    'label' => 'Tipo Vistoria: ',
                                    'name' => 'tipo_vistoria',
                                    'id' => 'tipo_vistoria'
                                ])
                                    @slot('options')
                                        <option value="">Selecione</option>
                                        @foreach($vistoriaTipos as $tipo)
                                            @if($tipo->id != '3')
                                                <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                            @endif
                                        @endforeach
                                    @endslot
                                @endcomponent
                            </div>
                            <div class="row" id="aberturaRow" style="display:none">

                                @component('components._input',[
                                    'size' => '6',
                                    'type' => 'date',
                                    'label' => 'Data de Abertura: ',
                                    'name' => 'data_abertura',
                                    'id' => 'data_abertura'
                                ])
                                @endcomponent

                                @component('components._input',[
                                    'size' => '6',
                                    'type' => 'file',
                                    'label' => 'Arquivo: ',
                                    'name' => 'arquivo_folha',
                                    'id' => 'arquivo_folha'
                                ])
                                @endcomponent


                            </div>
                            <div id="trasnferenciaRow" style="display:none;">
                                <div class="row">
                                    @component('components._input',[
                                          'size' => '6',
                                          'type' => 'date',
                                          'label' => 'Data do LO: ',
                                          'name' => 'data_lo',
                                          'id' => 'data_lo'
                                      ])
                                    @endcomponent

                                    @component('components._input',[
                                        'size' => '6',
                                        'type' => 'file',
                                        'label' => 'Arquivo do LO: ',
                                        'name' => 'arquivo_lo',
                                        'id' => 'arquivo_lo'
                                    ])
                                    @endcomponent
                                </div>
                                <div class="row">
                                    @component('components._select',[
                                           'size' => '6',
                                           'label' => 'Funcionarios: ',
                                           'name' => 'funcionario',
                                           'id' => 'funcionario'
                                       ])
                                        @slot('options')
                                            <option value="">Selecione</option>
                                            @foreach($vistoriaTipos as $tipo)
                                                @if($tipo->id != '3')
                                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                                @endif
                                            @endforeach
                                        @endslot
                                    @endcomponent

                                        @component('components._input',[
                                         'size' => '6',
                                         'type' => 'text',
                                         'label' => '%Atual Global',
                                         'name' => 'media_global',
                                         'id' => 'media_global'
                                     ])
                                        @endcomponent
                                </div>
                                <div class="row">

                                    @component('components._select',[
                                            'size' => '6',
                                            'label' => 'Andamento: ',
                                            'name' => 'andamento',
                                            'id' => 'andamento'
                                        ])
                                        @slot('options')
                                            <option value="">Selecione</option>
                                            @foreach($vistoriaTipos as $tipo)
                                                @if($tipo->id != '3')
                                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                                @endif
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                </div>

                                <div class="row">
                                    @component('components._select',[
                                            'size' => '6',
                                            'label' => 'Ritmo: ',
                                            'name' => 'ritmo',
                                            'id' => 'ritmo'
                                        ])
                                        @slot('options')
                                            <option value="">Selecione</option>
                                            @foreach($vistoriaTipos as $tipo)
                                                @if($tipo->id != '3')
                                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                                @endif
                                            @endforeach
                                        @endslot
                                    @endcomponent

                                    @component('components._input',[
                                       'size' => '6',
                                       'type' => 'date',
                                       'label' => 'Previsão Término',
                                       'name' => 'prev_termino',
                                       'id' => 'prev_termino'
                                   ])
                                    @endcomponent
                                </div>

                                @component('components._card')
                                    @slot('title')
                                       Ultimas Vistorias
                                    @endslot
                                    @slot('body')
                                        @slot('slot')
                                            <div class="row">
                                                <table>
                                                    <thead>
                                                        <th>Item</th>
                                                        <th>Data</th>
                                                        <th>%</th>
                                                        <th>%Atual</th>
                                                        <th>Observação</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        @endslot
                                    @endslot
                                @endcomponent


                            </div>
                            <div id="submite" style="display:none;">
                            @component('components.buttons._submit')@endcomponent
                            </div>

                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            //MASK
            $('#codigo').mask('0000/00000');
            $('#codigo_predio').mask('00.00.000');
            $('#telefone').mask('(00) 00000-0000');
            $('#media_global').mask('000,0%')
        });

        $("#codigo").change(function () {
            //function carrega predio.
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "/carrega/pi",
                data: 'codigoPi=' + $(this).val(),
                success: function (data) {

                    // $(location).attr('href', "{{ URL::to(Request::path()) }}");
                    var json = $.parseJSON(data);
                    $("#codigo").val(json.codigo)
                    //$("#id_predio").val(json.predios[0].id);
                    $("#predio").val(json.predios[0].name);
                    $("#diretoria").val(json.predios[0].diretoria);

                    $("#tipoVistoria").show();

                },
                error: function () {
                    alert('Por favor informe um código de processo de intervenção valido !');
                }
            });
        });

        $("#tipo_vistoria").change(function () {
            var tipoVistoria = $(this).val();
            //Refatorar regra de visualização quando cadastro estiver finalizado
            if (tipoVistoria == 1) {
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
    </script>
@endpush
