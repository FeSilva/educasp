@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])
@php
    $displayNoneTrans ="";
    $displayNoneAbertura = "";
    $displayNone  = '';

    if($vistoria->tipo_id == '1'){
        $displayNoneTrans="display:none;";
    }else{
       $displayNoneAbertura="display:none;";
    }
@endphp
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
                'title' => 'Cadastro de Vistoria',
                'route' => '#',
                'titleBtn' => 'Ver Infos',
                'id' => 'infos'
            ])
                @slot('body')
                    @component('components._form',[
                        'method' => 'POST',
                        'action' => route('vistorias.create'),
                        'attributes' => 'enctype=multipart/form-data accept=application/pdf'
                    ])
                        @slot('slot')

                            @component('components._input', [
                                        'size' => '2',
                                        'type' => 'hidden',
                                        'name' => 'id',
                                        'id' => 'id',
                                        'attributes' => 'readonly',
                                        'value' => old('id',$vistoria->id)
                                    ])
                            @endcomponent
                            <div class="col-md-12">
                                <div class="row">
                                    @component('components._input', [
                                        'size' => '2',
                                        'type' => 'text',
                                        'label' => 'Código PI:',
                                        'name' => 'codigo_pi',
                                        'id' => 'codigo_pi',
                                        'attributes' => 'readonly',
                                        'value' => old('codigo_pi',$vistoria->codigo)
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                            <div id="tipoVistoria" style="{{$displayNone}}">
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
                                            'value' => $vistoria->pi->predios[0]->name
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
                                            'value' => $vistoria->pi->predios[0]->diretoria
                                        ])
                                        @endcomponent
                                    </div>

                                    <div class="row">

                                        @component('components._select',[
                                            'size' => '3',
                                            'label' => 'Tipo Vistoria: ',
                                            'name' => 'tipo_vistoria',
                                            'id' => 'tipo_vistoria'
                                        ])
                                            @slot('options')
                                                <option selected disabled>Selecione</option>
                                                @if(empty($vistoria->id))
                                                    @foreach($vistoriaTipos as $tipo)
                                                        @if($tipo->vistoria_tipo_id != '3')
                                                            <option
                                                                value="{{ $tipo->vistoria_tipo_id }}" {{ (old('tipo_vistoria') == $tipo->vistoria_tipo_id) ? 'selected' : '' }}>{{ $tipo->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <option value="{{ $vistoria->tipo_id }}"
                                                            selected>{{ $vistoria->tipos->name }}</option>
                                                @endif
                                            @endslot
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row" id="aberturaRow" style="{{$displayNoneAbertura}}">

                                    @component('components._input',[
                                        'size' => '6',
                                        'type' => 'date',
                                        'label' => 'Data de Abertura: ',
                                        'name' => 'data_abertura',
                                        'id' => 'data_abertura',
                                        'value' => old('data_abertura',$vistoria->dt_vistoria)
                                    ])
                                    @endcomponent

                                    @if(empty($vistoria->arquivo))
                                        @component('components._input',[
                                            'size' => '3',
                                            'type' => 'file',
                                            'label' => 'Arquivo: ',
                                            'name' => 'arquivo_folha',
                                            'id' => 'arquivo_folha',
                                            'value' => old('arquivo_folha',$vistoria->arquivo)
                                        ])
                                        @endcomponent
                                    @else
                                        <div class="col-md-3">
                                            <label class="col-md- col-form-label">Arquivo:</label>
                                            <div class="form-group">
                                                <a href="{{$caminho}}"
                                                  >{{$vistoria->arquivo}}</a>
                                                <!-- onclick="downloadAnexo('{{$vistoria->arquivo}}','{{str_replace('/',"_",$vistoria->codigo)}}');"-->
                                            </div>
                                        </div>
                                    @endif


                                </div>
                            </div>
                            <div id="trasnferenciaRow" style="{{$displayNoneTrans}}">
                                <div class="col-md-12">
                                    <div class="row">

                                        @component('components._input',[
                                          'size' => '3',
                                          'type' => 'date',
                                          'label' => 'Data do LO: ',
                                          'name' => 'data_lo',
                                          'id' => 'data_lo',
                                          'value' => old('data_lo',$vistoria->dt_vistoria)
                                      ])
                                        @endcomponent
                                        @if(empty($vistoria->arquivo))
                                            @component('components._input',[
                                                'size' => '3',
                                                'type' => 'file',
                                                'label' => 'Arquivo do LO: ',
                                                'name' => 'arquivo_lo',
                                                'id' => 'arquivo_lo',
                                                'value' => old('arquivo_lo',$vistoria->arquivo_name)
                                            ])
                                            @endcomponent
                                        @else
                                            <div class="col-md-3">
                                                <label class="col-md- col-form-label">Arquivo:</label>
                                                <div class="form-group">
                                                    <a href="{{$caminho}}"
                                                    >{{$vistoria->arquivo}}</a>
                                                <!-- onclick="downloadAnexo('{{$vistoria->arquivo}}','{{str_replace('/',"_",$vistoria->codigo)}}');"-->
                                                </div>
                                            </div>
                                        @endif

                                        @component('components._input',[
                                            'size' => '3',
                                            'type' => 'number',
                                            'label' => 'Nº Funcionarios:',
                                            'name' => 'funcionario',
                                            'id' => 'funcionario',
                                            'value' => old('funcionario',$vistoria->funcionarios)
                                        ])
                                        @endcomponent
                                        @component('components._input',[
                                        'size' => '3',
                                        'type' => 'text',
                                        'label' => '%Atual Global',
                                        'name' => 'media_global',
                                        'id' => 'media_global',
                                        'value' => old('media_global',$vistoria->avanco_fisico)
                                        ])
                                        @endcomponent
                                    </div>

                                    <div class="row">
                                        @component('components._select',[
                                                'size' => '3',
                                                'label' => 'Andamento: ',
                                                'name' => 'andamento',
                                                'id' => 'andamento'
                                            ])
                                            @slot('options')
                                                <option disabled>Selecione</option>
                                                @if(!$vistoria->andamento_id)
                                                    @foreach($vistoriaAndamentos as $andamento)
                                                        <option
                                                            value="{{ $andamento->andamento_id }}" {{ (old('andamento') == $andamento->andamento_id) ? 'selected' : '' }}>{{ $andamento->name }}</option>
                                                    @endforeach
                                                @else
                                                    <option
                                                        value="{{ $vistoria->andamento_id }}"
                                                        selected}}>{{ $vistoria->andamentos->name }}</option>
                                                @endif
                                            @endslot
                                        @endcomponent

                                        @component('components._select',[
                                                'size' => '3',
                                                'label' => 'Ritmo: ',
                                                'name' => 'ritmo',
                                                'id' => 'ritmo'
                                            ])
                                            @slot('options')
                                                <option disabled>Selecione</option>
                                                @if(!$vistoria->ritmo_id)
                                                    @foreach($vistoriaRitmos as $ritmo)
                                                        <option
                                                            value="{{ $ritmo->ritmo_id }}" {{ (old('ritmo',$vistoria->ritmo_id) == $ritmo->ritmo_id) ? 'selected' : '' }}>{{ $ritmo->name }}</option>
                                                    @endforeach
                                                @else
                                                    <option
                                                        value="{{ $vistoria->ritmo_id }}"
                                                        selected>{{ $vistoria->ritmos->name }}</option>
                                                @endif
                                            @endslot
                                        @endcomponent

                                        @component('components._input',[
                                           'size' => '3',
                                           'type' => 'date',
                                           'label' => 'Previsão Término',
                                           'name' => 'prev_termino',
                                           'id' => 'prev_termino',
                                           'value' => old('prev_termino',$vistoria->prev_termino)
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
                                                </thead>
                                                <tbody>
                                                    @if(isset($vistoria->andamentoItems->Items[0]))
                                                        <tr>
                                                            <td>{{$vistoria->andamentoItems->Items[0]->num_item}}</td>
                                                            <td>{{$vistoria->andamentoItems->Items[0]->tipo_item}}</td>
                                                            <td>{{$vistoria->andamentoItems->Items[0]->prazo}}</td>
                                                            <td>{{number_format($vistoria->andamentoItems->Items[0]->valor,2,",",".")}}</td>
                                                            <td>{{date('d/m/y',strtotime($vistoria->dt_vistoria))}}</td>
                                                            <td>{{empty($vistoria->andamentoItems->progress) ? '00,0':$vistoria->andamentoItems->progress}}</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($vistoria->status == 'cadastro')
                                <div class="col-md-12">
                                    <div id="submite">
                                        @component('components.buttons._submit')@endcomponent
                                    </div>
                                </div>
                            @endif
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
    <!-- Modal -->
    @component('components._modal', [
        'title' => 'Infos',
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
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Código do Prédio:',
                            'name' => 'codigo_predio',
                            'id' => 'codigo_predio',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->predios[0]->codigo
                        ])
                        @endcomponent
                        @component('components._input', [
                            'size' => '5',
                            'type' => 'text',
                            'label' => 'Nome Prédio:',
                            'name' => 'name_predio',
                            'id' => 'name_predio',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->predios[0]->name
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '4',
                            'type' => 'text',
                            'label' => 'Diretoria:',
                            'name' => 'diretoria',
                            'id' => 'diretoria',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->predios[0]->diretoria
                        ])
                        @endcomponent
                    </div>
                    <div class="row">

                        @component('components._input', [
                            'size' => '3',
                            'type' => 'text',
                            'label' => 'Fiscal:',
                            'name' => 'fiscal',
                            'id' => 'fiscal',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->user[0]->name
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '5',
                            'type' => 'date',
                            'label' => 'Assinatura:',
                            'name' => 'assinatura',
                            'id' => 'assinatura',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->dt_assinatura
                        ])
                        @endcomponent
                        @component('components._input', [
                            'size' => '4',
                            'type' => 'text',
                            'label' => 'Valor Total:',
                            'name' => 'valor_total',
                            'id' => 'valor_total',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->valor_total
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
                            'value' => $vistoria->pi->prazo_total
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '5',
                            'type' => 'text',
                            'label' => 'Empresa Contratada:',
                            'name' => 'empresa_contratada',
                            'id' => 'empresa_contratada',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->empreiteiras[0]->name
                        ])
                        @endcomponent

                        @component('components._input', [
                            'size' => '4',
                            'type' => 'text',
                            'label' => 'Objeto do PI:',
                            'name' => 'objeto_pi',
                            'id' => 'objeto_pi',
                            'class' => 'input-escondido',
                            'attributes' => 'readonly',
                            'value' => $vistoria->pi->objeto_pi
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


            $('#infos').click(function () {
                $('#modalInfos').modal('show');
            });

            //MASK
            $('#codigo').mask('0000/00000');
            $('#codigo_predio').mask('00.00.000');
            $('#telefone').mask('(00) 00000-0000');
            $('#media_global').mask('##0,00', {reverse: true});

        });

        function openFieldsIfHasErrors(typeError) {
            switch (typeError) {
                case '1':
                    $("#tipoVistoria").show();
                    $("#aberturaRow").show();
                    $("#submite").show();
                    break;
                case '2':
                    $("#tipoVistoria").show();
                    $("#trasnferenciaRow").show();
                    $("#submite").show();
                    break;
            }
        }

        function downloadAnexo(filename, codigopi) {

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'post',
                url: "/vistorias/donwload",
                data: {filename: filename, codigoPi: codigopi},
                success: function (data) {
                    console.log(data);

                },
                error: function () {
                    alert('Erro ao realizar download por favor chame o suporte.');
                }
            });

        }

    </script>
    @if(session()->has('tipo_vistoria')))
    <script>
        openFieldsIfHasErrors("{{ session()->get('tipo_vistoria') }}")
    </script>
    @endif
@endpush
