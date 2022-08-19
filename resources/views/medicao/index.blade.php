@extends('layouts.app', [
'class' => '',
'elementActive' => 'medicao'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Medição</a></li>
        @endslot
    @endcomponent
    @component('components.messages._messages')@endcomponent
    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Lista de Medição',
            ])
                @slot('body')
                    <div class="row">
                        @component('components._input',[
                            'label' => 'Nome do Periodo',
                            'size' => '12',
                            'type' => 'text',
                            'name' => 'periodo_name',
                            'id' => 'periodo_name'

                        ])@endcomponent
                    </div>
                    <div class="row">
                        @component('components._input',[
                            'label' => 'Data de Inicio',
                            'size' => '6',
                            'type' => 'date',
                            'name' => 'dt_ini',
                            'id' => 'dt_ini'

                        ])@endcomponent
                        @component('components._input',[
                            'label' => 'Data Final',
                            'size' => '6',
                            'type' => 'date',
                            'name' => 'dt_fim',
                            'id' => 'dt_fim'
                        ])@endcomponent
                    </div>
                    
                    @component('components.buttons._submit',[
                        'type' => 'button',
                        'title' => 'Gerar Medição',
                        'id' => 'medicaoCreate',
                        'attributes' => "onClick=gerarMedicao()"
                    ])
                    @endcomponent

                    @component('components._table', ['class' => 'medicaoList'])
                        @slot('thead')
                            <th>#</th>
                            <th><span class="badge me-1 rounded-pill">Nome do Periodo</span></th>
                            <th align="center"><span class="badge me-1 rounded-pill">Total Vinculada</span></th>
                            <th><span class="badge me-1 rounded-pill">Data Inicial</span></th>
                            <th><span class="badge me-1 rounded-pill">Data Final</span></th>
                            <th align="center"><span class="badge me-1 rounded-pill">Ações</span></th>
                        @endslot
                        @slot('tbody')
                            @forelse($medicoes as $key => $medicao)
                                <tr>
                                    <td><span class="badge me-1 rounded-pill">{{ $medicao['medicao']->medicao_id }}</span></td>
                                    <td><span class="badge me-1 rounded-pill">{{ $medicao['medicao']->name }}</span></td>
                                    <td><span class="badge me-1 rounded-pill bg-success">{{ number_format($medicao['qtd_total']) }}</span></td>
                                    <td><span class="badge me-1 rounded-pill">{{date('d/m/y',strtotime($medicao['medicao']->dt_ini))}}</span></td>
                                    <td><span class="badge me-1 rounded-pill">{{date('d/m/y',strtotime($medicao['medicao']->dt_fim))}}</span></td>
                                    <td>
                                        <img src="{{asset("paper")}}/img/icons/view.png"
                                        onclick="window.location='{{ route('medicao.show',['medicao_id' => $medicao['medicao']->medicao_id]) }}'"
                                        width="20px">
                                    <td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" align="center">Nenhum Registro Encontrado</td>
                                </tr>
                            @endforelse
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

        });

        function gerarMedicao()
        {
            var medicao_name = $("#periodo_name").val();
            var dt_inicio = $("#dt_ini").val();
            var dt_fim = $("#dt_fim").val();
            
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('input[name="_token"]').val()
                },
                url:'/medicao/store',
                method: 'POST',
                data: {
                    name:medicao_name,
                    dt_inicio: dt_inicio,
                    dt_fim: dt_fim
                },
                beforeSend:function () {
                    alert('Um medição já esta sendo castrada !')
                },
                success: function(data) {
                    document.location.reload(true);
                },
                error: function(error) {
                    //document.location.reload(true);
                }
            })
        
        }
    </script>
@endpush