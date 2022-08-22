@extends('layouts.app', [
'class' => '',
'elementActive' => 'medicao'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="{{ route('medicao.list') }}">Medição</a></li>
            <li class="breadcrumb-item">{{ $medicao->name }}</li>
        @endslot
    @endcomponent
    @component('components.messages._messages')@endcomponent
    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Lista de Fiscais',
            ])
                @slot('body')
                    <div class="col-md-6">
                        <h6>Data Inicio: {{ date_format(date_create($medicao->dt_ini),'d/m/Y') }}</h6>
                    </div>
                    <div class="col-md-6">
                        <h6>Data Final: {{ date_format(date_create($medicao->dt_fim),'d/m/Y') }}</h6>
                    </div>
            
                    <div class="col-12">
                        @component('components._table', ['class' => 'medicaoFiscais'])
                            @slot('thead')
                                <th>#</th>
                                <th><span class="badge me-1 rounded-pill">Fiscal</span></th>
                                <th><span class="badge me-1 rounded-pill">Total Medido</span></th>
                                <th><span class="badge me-1 rounded-pill">Total Disponivel</span></th>
                                <th><span class="badge me-1 rounded-pill">R$ Valor</span></th>
                                <th><span class="badge me-1 rounded-pill">Despesas</span></th>
                                <th><span class="badge me-1 rounded-pill">Status</span></th>

                                <th align="center"><span class="badge me-1 rounded-pill">Ações</span></th>
                            @endslot
                            @slot('tbody')
                                @php 
                                    $totalMedido = 0;
                                    $totalDisponivel = 0;
                                    $totalValorMedido = 0;
                                    $totalDespesas = 0;
                                @endphp
                                @foreach($medicaoFiscais as $fiscais)
                                    @php 
                                        $totalMedido += $fiscais->total_medido;
                                        $totalDisponivel += $fiscais->total_disponivel;
                                        $totalValorMedido += $fiscais->valor_medido;
                                        $totalDespesas += $fiscais->despesas ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $fiscais->medicao_fiscal_id }}</td>
                                        <td><span class="badge" style="font-size:12px;">{{ $fiscais->fiscal_name }}</span></td>
                                        <td align="center"><span class="badge" style="font-size:12px;">{{ $fiscais->total_medido }}</span></td>
                                        <td align="center"><span class="badge" style="font-size:12px;">{{ $fiscais->total_disponivel }}</span></td>
                                        <td align="center"><span class="badge me-1 rounded-pill">R$ {{ number_format($fiscais->valor_medido, 2, ",", ".") }}</span></td>
                                        <td align="center"><span class="badge me-1 rounded-pill">{{ number_format($fiscais->despesas, 2, '.', ",") }}</span></td>
                                        
                                        <td align="center" id="statuspan_{{ $fiscais->medicao_fiscal_id }}"><span class="badge me-1 bg-4" > {{  $fiscais->status == 'I' ? 'Iniciada' : 'Finalizada' }}</span></td>
                                        
                                        <td>
                                            
                                            <img src="{{asset("paper")}}/img/icons/view.png"
                                            style="cursor: pointer;"
                                            onclick="window.location='{{route('medicao.fiscal.show',['medicao_id' => $fiscais->medicao_id, 'fiscal_id' => $fiscais->fiscal_id, 'status' => $fiscais->status])}}'"
                                            width="20px">
                                        
                                            
                                            @if ($fiscais->status == 'F')
                                                <img src="{{asset("paper")}}/img/icons/download.png"
                                                style="cursor: pointer;"
                                                onclick="download('{{ $fiscais->medicao_id }}','{{ $fiscais->fiscal_id }}')"
                                                //window.location='{{route('medicao.fiscal.relatorio.medicoes',['medicao_id' => $fiscais->medicao_id, 'fiscal_id' => $fiscais->fiscal_id])}}'
                                                width="20px">
                                            @else
                                               
                                                <img src="{{asset("paper")}}/img/icons/cadeado.png"
                                                style="cursor: pointer;"
                                                onclick="alterarStatus('{{ $fiscais->medicao_fiscal_id }}')"
                                                width="20px">
                                            @endif
                                            
                                    </tr>
                                @endforeach
                            @endslot
                            @slot('tfoot')
                                    <tr>
                                        <td colspan="2" align="center"><strong>Total:</strong></td>
                                        <td align="center"><span class="badge me-1 rounded-pill bg-primary">{{ $totalMedido }}</span></td>
                                        <td align="center"><span class="badge me-1 rounded-pill bg-warning">{{ number_format($totalDisponivel, 2, ",", ".") }}</span></td>
                                        <td align="center"><span class="badge me-1 rounded-pill bg-success">R$ {{ number_format($totalValorMedido, 2, ",", ".") }}</span></td>
                                        <td align="center"><span class="badge me-1 rounded-pill bg-success">R$ {{ number_format($totalDespesas, 2, ",", ".") }}</span></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                            @endslot
                        @endcomponent
                    </div>
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

<div class="modal fade" id="download_option" tabindex="-1" role="dialog" aria-labelledby="download_option" aria-hidden="true">
    <div class="modal-dialog" role="download">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalhes_vistorias_title"></h5>
                <input type='hidden' name='vistoria_id_details' />
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-disponiveis">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Escolhe uma Opção de Documento para Realizar o Download</p>
                <button class="btn btn-default">Medição</button> <button class="btn btn-default">Depesas</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $('.medicaoFiscais').DataTable({
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

        function alterarStatus(medicao_fiscal_id) 
        {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                type: 'POST',
                url: "{{ route('medicao.fiscal.status') }}",
                data: {medicao_fiscal_id: medicao_fiscal_id},
                beforeSend: function () {
                    $('#loading').modal('show');
                },
                success: function (data) {
                    $('#loading').modal('hide');
                },
                error: function(error){
                    alert(error);
                }
            });
        }
        function download(medicao_id, fiscal_id)
        {
            window.open("/medicao/relatorio/fiscal/"+fiscal_id+"/medicao/"+medicao_id+"/despesas", '_blank');
            return window.open("/medicao/relatorio/fiscal/"+fiscal_id+"/medicao/"+medicao_id, '_blank');
        }
    </script>
@endpush