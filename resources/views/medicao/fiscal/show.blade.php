@extends('layouts.app', [
'class' => '',
'elementActive' => 'medicao'
])
@component('components._loading') @endcomponent
@section('content')

    @component('components._breadcrumb')
        @slot('list')
        <li class="breadcrumb-item"><a href="{{ route('medicao.list') }}">Medição</a></li>
        <li class="breadcrumb-item"><a href="{{ route('medicao.show', ['medicao_id' => $medicao->medicao_id]) }}">{{ $medicao->name }}</a></li>
        <li class="breadcrumb-item">{{ $fiscal->name }}</li>
        @endslot
    @endcomponent
    @component('components.messages._messages')@endcomponent
    @component('components._content')
        <div class="container">
         
            @slot('slot')
                @component('components._card', [
                    'title' => 'Lista de Vistorias',
                ])
                    @slot('body')
                        <div class="col-12">
                            <form>
                                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                            </form>
                            @component('components._table', ['class' => 'fiscal_detalhe'])
                        
                                @slot('thead')
                                    <th>#</th>
                                    <th><span class="badge me-1">Tipos Vistoria</span></th>
                                    <th><span class="badge me-1">Total Medido</span></th>
                                    <th><span class="badge me-1">R$ Valor</span></th>
                                    <th><span class="badge me-1">Total Disponivel</span></th>
                                    <th><span class="badge me-1">Total Pendentes</span></th>
                                    <th align="center"><span class="badge me-1">Ações</span></th>

                                @endslot
                                @slot('tbody')
                                    @foreach($fiscalDetalhes as $detalhes)
                                            <tr>
                                                <td></td>
                                                <td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;"><span class="badge me-1">{{ $detalhes->vistoria_tipo }}</span></td>
                                                <td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;" align="center"><span class="badge me-1">{{ $detalhes->TotalMedido }}</span></td>
                                                <td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;" align="center"><span class="badge me-1 bg-success">{{ number_format($detalhes->ValorMedido, 2, ",", ".") }}</span></td>
                                                <td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;" align="center"><span class="badge me-1">{{ $detalhes->TotalDisponivel }}</span></td>
                                                <td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;" align="center"><span class="badge me-1">{{ $detalhes->TotalPendentes }}</span></td>
                                                @php 
                                                /*<td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;"><span class="badge me-1 bg-">{{ number_format($detalhes->valorDisponivel, 2, ",", ".") }}</span></td>
                                                <td onClick="vistorias_show({{ $detalhes->tipo_id }})" style="cursor: pointer;"><span class="badge me-1 bg-">{{ number_format($detalhes->valorPendentes, 2, ",", ".") }}</span></td>*/
                                                @endphp
                                                @if($detalhes->TotalDisponivel >= 1 and $status == 'I')
                                                    <td style="cursor:pointer;">
                                                        <img src="{{asset("paper")}}/img/icons/vincular.png"
                                                        onClick="vistorias_details({{ $detalhes->tipo_id }})"
                                                        width="20px">
                                                    </td>
                                                @else 
                                                    <td style="cursor:ponter;">
                                                        <img src="{{asset("paper")}}/img/icons/vincular.png"
                                                        title='Sem Vistorias Disponiveis'
                                                        width="20px">
                                                    </td>
                                                @endif
                                            </tr>
                                    @endforeach
                                @endslot
                            @endcomponent
                        </div>
                    @endslot
                @endcomponent


                <div class="modal fade" id="medir_vistorias" tabindex="-1" role="dialog" aria-labelledby="medir_vistorias" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document" style="max-width: 95%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detalhes_vistorias_title"></h5>
                                <input type='hidden' name='vistoria_id_details' />
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-disponiveis">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            
                                @component('components._card', [
                                    'title' => 'Disponiveis para Medição',
                                ])
                                    @slot('body')
                                        <div class="col-12">
                                            @component('components._table', [
                                                'class' => 'vistoria_details_disponiveis',
                                                'body_id' => "detalhes_vistoria_body_disponiveis",
                                            ])
                                                @slot('thead')
                                                    <th>#</th>
                                                    <th>Código</th>
                                                    <th>Prédio</th>
                                                    <th>Data da Vistoria</th>
                                                    <th>Medir Vistoria</th>
                                                @endslot
                                                @slot('tbody')
                                                @endslot
                                            @endcomponent
                                        </div>
                                    @endslot
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="show_vistorias" tabindex="-1" role="dialog" aria-labelledby="show_vistorias" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document" style="max-width: 95%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detalhes_vistorias_title"></h5>
                                <input type='hidden' name='vistoria_id_details' />
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            

                                @component('components._card', [
                                    'title' => 'Medidas',
                                    'cardId' => 'cardMedidas'
                                ])
                                    @slot('body')
                                        <div class="col-12" id="medidas">
                                            @component('components._table', [
                                                'class' => 'vistoria_details_medidas',
                                                'body_id'=> 'detalhes_vistoria_body_medidas',
                                            ])
                                                @slot('thead')
                                                    <th>#</th>
                                                    <th>Código</th>
                                                    <th>Prédio</th>
                                                    <th>Data da Vistoria</th>
                                                @endslot
                                                @slot('tbody')
                                                @endslot
                                            @endcomponent
                                        </div>
                                    @endslot
                                @endcomponent
                 
                                @component('components._card', [
                                    'title' => 'Pendentes',
                                    'cardId' => 'cardPendentes'
                                ])
                                    @slot('body')
                                        <div class="col-12" id = "pendentes">
                                            @component('components._table', [
                                                'class' => 'vistoria_details_pendentes',
                                                'body_id' => "detalhes_vistoria_body_pendentes",
                                            ])
                                                @slot('thead')
                                                    <th>#</th>
                                                    <th>Código</th>
                                                    <th>Prédio</th>
                                                    <th>Data da Vistoria</th>
                                                @endslot
                                                @slot('tbody')
                                                @endslot
                                            @endcomponent
                                        </div>
                                    @endslot
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>

            
                @component('components._card', [
                    'title' => 'Lista de Despesas',
                    'route' => "#",
                    'attributes' => 'onClick=modalDespesas()',
                ])
                    @slot('body')
                        <div class="col-12">
                            @component('components._table', ['class' => 'lista_despesas'])
                                @slot('thead')
                                    <th>#</th>
                                    <th>Tipos de Despesa</th>
                                    <th>Data do Recibo</th>
                                    <th>R$ Valor</th>
                                    <th align="center">Ações</th>
                                @endslot
                                @slot('tbody')
                                    @foreach ($despesas as $despesa)
                                        @php 
                                            $dt_recibo = date_create($despesa->dt_recibo);
                                        @endphp
                                        <tr>
                                            <td>{{ $despesa->id }}</td>
                                            <td>{{ $despesa->type }}</td>
                                            <td>{{ date_format($dt_recibo,"d/m/Y") }}</td>
                                            <td>{{ $despesa->amount }}</td>
                                            <td>
                                                <a href="#">
                                                    <img src="{{asset("paper")}}/img/icons/edit.png"  onclick="modalDespesas({{$despesa->id}})"  width="30px">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endslot
                            @endcomponent
                        </div>
                    @endslot
                @endcomponent

                @component('components._card', [
                    'title' => 'Lista de Anexos',
                    'route' => "#",
                    'attributes' => 'onClick=modalAnexos()',
                ])
                    @slot('body')
                        <div class="col-12">
                            @component('components._table', ['class' => 'lista_anexos'])
                                @slot('thead')
                                    <th>#</th>
                                    <th>Anexo</th>
                                    <th>Valor</th>
                                    <th align="center">Ações</th>
                                @endslot
                                @slot('tbody')
                                    @foreach ($anexos as $anexo)
                                    <tr>
                                        <td></td>
                                        <td>
                                            <a href={{  asset("{$anexo->path}.pdf") }} target="_blank">
                                                {{ $anexo->name }}
                                            </a>
                                        </td>
                                        <td>{{ $anexo->amount }}</td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                @endslot
                            @endcomponent
                        </div>
                    @endslot
                @endcomponent

            @endslot
        </div>
    @endcomponent
@endsection


<div class="modal fade" id="show_despesas_modal" tabindex="-1" role="dialog" aria-labelledby="show_despesas_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="show_despesas_title">Despesas</h5>
                <input type='hidden' name='show_id_despesas' />
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @component('components._input',[
                        'label' => 'Nº do Recibo',
                        'size' => '3',
                        'type' => 'text',
                        'name' => 'n_recibo',
                        'id' => 'n_recibo'

                    ])@endcomponent
                    @component('components._input',[
                        'label' => 'Data do Recibo',
                        'size' => '3',
                        'type' => 'date',
                        'name' => 'dt_recibo',
                        'id' => 'dt_recibo'

                    ])@endcomponent

                    @component('components._input',[
                        'label' => 'Valor',
                        'size' => '3',
                        'type' => 'text',
                        'name' => 'amount',
                        'id' => 'amount_despesas'

                    ])@endcomponent
               
                    @component('components._select',[
                        'size' => '3',
                        'label' => 'Tipo do recibo',
                        'name' => 'type',
                        'id' => 'type'
                    ])
                        @slot('options')
                            <option selected disabled>Selecione</option>
                                <option value='pedagio'>Pedagio</opetion>
                        @endslot
                    @endcomponent
                </div>
                
                @component('components.buttons._submit',[
                    'type' => 'button',
                    'title' => 'Buscar Vistorias',
                    'id' => 'gerarVistoriasDespesas',
                    'attributes' => "onClick=vistoriasDespesasList()"
                ])
                @endcomponent
                @component('components._card', [
                    'title' => 'Vistorias - Fiscalização',
                ])
                    @slot('body')
                        <div class="col-12" id="row_vistorias_despesas">
                            @component('components._table', [
                                'class' => 'vistorias_despesas_details',
                                'body_id'=> 'vistorias_despesas_body',
                            ])
                                @slot('thead')
                                    <th>#</th>
                                    <th>Tipo da Vistoria</th>
                                    <th>Código</th>
                                    <th>Nome da Escola</th>
                                    <th>Data da Vistoria</th>
                                @endslot
                                @slot('tbody')
                                @endslot
                            @endcomponent
                        </div>
                    @endslot
                @endcomponent

                @component('components.buttons._submit',[
                    'type' => 'button',
                    'title' => 'Criar Despesa',
                    'idrow' => 'createDespesaRow',
                    'id' => 'createDespesa',
                    'attributes' => "onClick=createDespesas()"
                ])
                @endcomponent
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="show_despesas_update_modal" tabindex="-1" role="dialog" aria-labelledby="show_despesas_update_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 95%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="show_despesas_update_title">Despesa</h5>
                <input type='hidden' name='show_id_despesas_update' />
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vistorias.despesas.update') }}" method="POST">
                    @csrf
                    <input type='hidden' name='despesa_id' id='despesa_id' value/>

                    <div class="row">

                        @component('components._card', [
                            'title' => 'Vistorias Atreladas a Despesa',
                        ])
                            @slot('body')
                                <div class="row">
                                    <input type="hidden" name="medicao_update_id" id="medicao_update_id" value="{{ $medicao->medicao_id }}">
                                    @component('components._input',[
                                        'label' => 'Nº do Recibo',
                                        'size' => '3',
                                        'type' => 'text',
                                        'name' => 'n_recibo_update',
                                        'id' => 'n_recibo_update'
                
                                    ])@endcomponent
                                    @component('components._input',[
                                        'label' => 'Data do Recibo',
                                        'size' => '3',
                                        'type' => 'date',
                                        'name' => 'dt_recibo_update',
                                        'id' => 'dt_recibo_update'
                
                                    ])@endcomponent
                
                                    @component('components._input',[
                                        'label' => 'Valor',
                                        'size' => '3',
                                        'type' => 'text',
                                        'name' => 'amount_update',
                                        'id' => 'amount_update'
                
                                    ])@endcomponent
                            
                                    @component('components._select',[
                                        'size' => '3',
                                        'label' => 'Tipo do recibo',
                                        'name' => 'type_update',
                                        'id' => 'type_update'
                                    ])
                                        @slot('options')
                                            <option selected disabled>Selecione</option>
                                                <option value='pedagio'>Pedagio</opetion>
                                        @endslot
                                    @endcomponent
                                </div>
                                <div class="col-12" id="row_vistorias_despesas_update">
                                    @component('components._table', [
                                        'class' => 'vistorias_despesas_update_details',
                                        'body_id'=> 'vistorias_despesas_update_body',
                                    ])
                                        @slot('thead')
                                            <th>#</th>
                                            <th>Nome da Escola</th>
                                            <th>Código</th>
                                            <th>Data da Vistoria</th>
                                        @endslot
                                        @slot('tbody')
                                        @endslot
                                    @endcomponent
                                </div>
                            @endslot
                        @endcomponent

                        @component('components._card', [
                            'title' => 'Vistorias Disponiveis',
                        ])
                            @slot('body')
                                <div class="col-12" id="row_vistorias_despesas_disponiveis_update">
                                    @component('components._table', [
                                        'class' => 'vistorias_despesas_update_disponiveis_details',
                                        'body_id'=> 'vistorias_despesas_update_disponiveis_body',
                                    ])
                                        @slot('thead')
                                            <th>#</th>
                                            <th>Tipo de Vistorias</th>
                                            <th>Nome da Escola</th>
                                            <th>Código</th>
                                            <th>Data da Vistoria</th>
                                        @endslot
                                        @slot('tbody')
                                        @endslot
                                    @endcomponent
                                </div>
                            @endslot
                        @endcomponent

                       
                    </div>
                    <div class="col-12">
                        @component('components.buttons._submit',[
                            'type' => 'submit',
                            'title' => 'Atualizar Despesa',
                            'idrow' => 'updateDespesaRow',
                            'id' => 'updateDespesa',
                        ])
                        @endcomponent
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_anexos" tabindex="-1" role="dialog" aria-labelledby="modal_anexos" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="anexos_title">Anexo</h5>
                <input type='hidden' name='show_id_despesas' />
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="col-md-12" action="{{ route('medicao.fiscal.anexos') }}" method="POST" style="overflow: auto" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                    <input type="hidden" name="medicao_id"  value="{{ $medicao->medicao_id }}" />
                    <input type="hidden" name="fiscal_id"  value="{{ $fiscal->id }}" />
                    <div class="row">
                        @component('components._input',[
                            'label' => 'Nome do Arquivo',
                            'size' => '4',
                            'type' => 'text',
                            'name' => 'name_archive',
                            'id' => 'name_archive'
                        ])@endcomponent
                        @component('components._input',[
                            'label' => 'Valor do Documento',
                            'size' => '4',
                            'type' => 'text',
                            'name' => 'amount',
                            'id' => 'amount'
                        ])@endcomponent

                        @component('components._input',[
                            'label' => 'archive',
                            'size' => '4',
                            'type' => 'file',
                            'name' => 'archive',
                            'id' => 'archive'
                        ])@endcomponent
                    </div>
                    @component('components.buttons._submit',[
                        'type' => 'submit',
                        'title' => 'Anexar Documento',
                        'id' => 'gravarAnexo',
                    ])
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        $(document).ready(function () {
            $("#close-disponiveis").click(function (){
                document.location.reload(true);
            });
            $("#amount").mask('#.##0,00', {
                reverse: true
            });

            $("#amount_despesas").mask('#.##0,00', {
                reverse: true
            });
        }); 

        $("#cardMedidas").click(function(e) {
            $("#medidas").toggle();
        });
        $("#cardPendentes").click(function(e) {
            $("#pendentes").toggle();
        });

        function vistorias_details(tipo_id)
        {
            typeVistoriaTitle(tipo_id);
            if (tipo_id == 2) {
                tipo_id = ['2','3'];
            }
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "{{ route('medicao.fiscal.vistoriaetails') }}",
                data: {tipo_id: tipo_id, medicao_id: {{ $medicao->medicao_id }}, fiscal_id: {{ $fiscal->id }}},
                beforeSend: function () {
                    $("#loading").modal('show');
                },
                success: function (data) {
                    $('#loading').modal('hide');
                    $('#medir_vistorias').modal('show');
                    $("#detalhes_vistoria_body_disponiveis").html('');
                    $.each(data.disponiveis, function (index, valueDisponiveis) {
                        $('#detalhes_vistoria_body_disponiveis').append("<tr>"+
                            
                                "<td>" + valueDisponiveis.vistoria_id + "</td>"+
                                "<td>" + valueDisponiveis.codigo + '</td>' +
                                "<td>" + valueDisponiveis.cod_predio + '</td>' +
                                "<td>" + valueDisponiveis.date_vistoria + '</td>' +
                                "<td> <input type='checkbox' name='medir_vistoria' onClick='medirVistoria("+valueDisponiveis.vistoria_id+","+tipo_id+")'></td>"
                                + '</tr>');
                    });
                },
                error: function(error){
                    alert(error);
                }
            });
        }

        function vistorias_show(tipo_id)
        {
            typeVistoriaTitle(tipo_id);
            if (tipo_id == 2) {
                tipo_id = ['2','3'];
            }
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "{{ route('medicao.fiscal.vistoriaetails') }}",
                data: {tipo_id: tipo_id, medicao_id: {{ $medicao->medicao_id }}, fiscal_id: {{ $fiscal->id }}},
                success: function (data) {
                    $('#show_vistorias').modal('show');
                    $("#detalhes_vistoria_body_medidas").html('');
                    $.each(data.medidas, function (index, value) {
                        $('#detalhes_vistoria_body_medidas').append("<tr>"+
                                "<td>" + value.vistoria_id +"</td>"+
                                "<td>" + value.codigo + '</td>' +
                                "<td>" + value.cod_predio + '</td>' +
                                "<td>" + value.date_vistoria + '</td>' +
                                + '</tr>');
                    });

                    $("#detalhes_vistoria_body_pendentes").html('');
                    $.each(data.pendentes, function (index, valuePendentes) {
                        $('#detalhes_vistoria_body_pendentes').append("<tr>"+
                                "<td>" + valuePendentes.vistoria_id + "</td>"+
                                "<td>" + valuePendentes.codigo + '</td>' +
                                "<td>" + valuePendentes.cod_predio + '</td>' +
                                "<td>" + valuePendentes.date_vistoria + '</td>' +
                                + '</tr>');
                    });

                },
                error: function(error){
                    alert(error);
                }
            });
        }

        function medirVistoria(vistoria_id, tipo_id)
        {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "{{ route('medicao.fiscal.medirVistoria') }}",
                data: {medicao_id: {{ $medicao->medicao_id }},vistoria_id: vistoria_id, tipo_id: tipo_id},
                beforeSend: function () {
                    $('#loading').modal('show');    
                },
                success: function (data) {
                    $('#loading').modal('hide');
                    //document.location.reload(true);
                }
            });
        }

        function typeVistoriaTitle(tipo_id) 
        {
            var title = '';
            $("#detalhes_vistorias_title").html("");
            switch(tipo_id) {
                case 1:
                    title = 'Fiscalização';
                    break;
                case 2:
                    title = 'Transferência';
                    break;
                case 3:
                    title = 'Fiscalização';
                    break;
                case 6: 
                    title = 'Específica';
                    break;
                case 5:
                    title = "Orçamento Complexo";
                    break;
                case 4:
                    title = "Orçamento Simples";
                    break; 
            }
            $("#detalhes_vistorias_title").append(title);
        }

        function modalDespesas(despesa_id = null)
        {
            if ('{{ $status }}' == 'I') {
                if (despesa_id) {
                    $('#show_despesas_update_modal').modal('show');
                    vistoriasDespesasList(despesa_id);
                } else {
                    $("#createDespesaRow").hide();
                    $('#show_despesas_modal').modal('show');
                    $('#row_vistorias_despesas').hide();
                }
            } else{ 
                alert("Medição foi Finalizada.")
            }
        }

        function vistoriasDespesasList(despesa_id = null)
        {
            var dt_recibo = $("#dt_recibo").val();

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "{{ route('vistorias.despesas.list') }}",
                data: {
                    dt_recibo: dt_recibo,
                    medicao_id: {{ $medicao->medicao_id }},
                    fiscal_id: {{ $fiscal->id }},
                    despesa_id: despesa_id 
                },
                beforeSend: function (data) {
                    //
                },
                success: function (data) {
                    
                    if (despesa_id) {
                        $("#despesa_id").val(despesa_id)
                        $("#vistorias_despesas_update_body").html('');
                        $("#vistorias_despesas_update_disponiveis_body").html('');
                        $("#n_recibo_update").val(data.vistoriasDespesas[0].n_recibo);
                        $("#dt_recibo_update").val(data.vistoriasDespesas[0].data_recibo);
                        $("#amount_update").val(data.vistoriasDespesas[0].valor);
                        $("#type_update").val(data.vistoriasDespesas[0].tipo_despesa);
                        if (data.modal) {
                            $.each(data.vistoriasDespesas, function (index, value) {
                                $('#vistorias_despesas_update_body').append("<tr>"+
                                        "<td>"+
                                        "<input type='hidden' name='fiscal_id' id='fiscal_id' value='{{ $fiscal->id }}'>"+
                                        "<input type='hidden' name='vistoria_tipo_update_"+value.id+"' id='vistoria_tipo_update_"+value.id+"' value='"+value.tipo_id+"'>"+
                                        "<input type='checkbox' name='vistorias_despesa_update_check[]' value='"+value.id+"' checked></td>"+
                                        "<td>" + value.tipo_vistoria + '</td>' +
                                        "<td>" + value.escola + '</td>' +
                                        "<td>" + value.codigo +"</td>"+
                                        "<td>" + value.dt_vistoria + '</td>' +
                                        + '</tr>');
                            });

                            $.each(data.vistoriasDespesasDisponiveis, function (index, value) {
                                console.log(value);
                                $('#vistorias_despesas_update_disponiveis_body').append("<tr>"+
                                        "<td>"+
                                        "<input type='hidden' name='fiscal_id' id='fiscal_id' value='{{ $fiscal->id }}'>"+
                                        "<input type='hidden' name='vistoria_tipo_update_"+value.id+"' id='vistoria_tipo_update_"+value.id+"' value='"+value.tipo_id+"'>"+
                                        "<input type='checkbox' name='vistorias_despesa_update_check[]' value='"+value.id+"'></td>"+
                                        "<td>" + value.tipo_vistoria + '</td>' +
                                        "<td>" + value.escola + '</td>' +
                                        "<td>" + value.codigo +"</td>"+
                                        "<td>" + value.dt_vistoria + '</td>' +
                                        + '</tr>');
                            });
                        }
                        return;
                    }

                    $("#vistorias_despesas_body").html('');
                    $.each(data, function (index, value) {
                        $('#vistorias_despesas_body').append("<tr>"+
                                "<td>" +
                                "<input type='hidden' name='vistoria_tipo_"+value.id+"' id='vistoria_tipo_"+value.id+"' value='"+value.tipo_id+"'>"+
                                "<input type='checkbox' name='vistorias_despesa_check[]' value='"+value.id+"'>"+
                                "</td>"+
                                "<td>" + value.tipo_vistoria + '</td>' +
                                "<td>" + value.escola + '</td>' +
                                "<td>" + value.codigo +"</td>"+
                                "<td>" + value.dt_vistoria + '</td>' +
                                + '</tr>');
                    });
                    $('#row_vistorias_despesas').show();
                    $("#createDespesaRow").show();
                },
                error: function(error){
                    alert(error);
                }
            });
        }

        function createDespesas()
        {
            var dt_recibo = $("#dt_recibo").val();
            var n_recibo = $("#n_recibo").val();
            var amount = $("#amount_despesas").val();
            var type = $("#type option:selected").val();
            var vistorias_id = [];
            var tipos_id = [];
            let checkbox_vistorias = $('input[name="vistorias_despesa_check[]"]');

            
            $.each(checkbox_vistorias, function (index, value) {
                if ($(value).is(':checked')) {
                    var ids = $(value).val();
                    var tipo = $("#vistoria_tipo_"+ids).val();
                    vistorias_id[index] = ids;
                    tipos_id[index] = tipo;
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "{{ route('vistorias.despesas.create') }}",
                data: {
                    dt_recibo: dt_recibo,
                    amount: amount,
                    n_recibo: n_recibo,
                    type: type,
                    medicao_id: {{ $medicao->medicao_id }},
                    fiscal_id: {{ $fiscal->id }},
                    vistorias_id:vistorias_id,
                    tipos_id:tipos_id
                },
                beforeSend: function (data) {
                    //
                },
                success: function (data) {
                    document.location.reload(true);
                },
                error: function(error){
                    alert(error);
                }
            });
            
        }


        function modalAnexos()
        {
            if ('{{ $status }}' == 'I') {
                $("#modal_anexos").modal('show');
            } else {
                alert("Medição foi Finalizada.")
            }
        }
        
        $('.vistorias_despesas_details').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

        $('.vistoria_details_disponiveis').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

        $('.vistoria_details_pendentes').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

        $('.vistoria_details_medidas').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

        $('.fiscal_detalhe').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

        $('.lista_anexos').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

        $('.lista_despesas').DataTable({
            language: {
                "lengthMenu": "registros _MENU_ por página",
                "zeroRecords": "Nenhum registro encontrado",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(Total de  _MAX_ total records)",
                "search": "Pesquisar:",
                "paginate": {
                    "first": "Primeiro",
                    "last": "Last",
                    "next": "Próximo",
                    "previous": "Anterior"
                }
            }
        });

    </script>
@endpush