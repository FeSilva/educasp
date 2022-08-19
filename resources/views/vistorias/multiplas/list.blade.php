@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistoriaMultiplas'
])
<style>
    .vistorias span{
        display:none;
    }
</style>
@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Usuários</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Cadastro de Vistorias',
                'route' => route('multiplas.store'),
            ])
                @slot('body') 
                @include('vistorias.partials.form_filter',['route'=> 'vistoriasMultiplas.filterList'])
                <div class="text-right">
                    <a href="{{ route('zipArchiveMultiplos.index') }}" class="btn btn-sm btn-primary" target='_blank' id="uploadArchive">Upload Archive</a>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#filterModal">
                        Filtros por Datas
                    </button>
                </div>
                    @component('components._table', ['class' => 'vistorias'])
                        @slot('thead')
                            <th>Tipo de Vistoria</th>
                            <th>Fiscal Responsável</th>
                            <th>Código PI / Prédio</th>
                            <th>Data da Vistoria</th>
                            <th>Ref. (mês) de Envio</th>
                            <th>Status</th>
                            <th align="center">Ações</th>
                        @endslot
                        @slot('tbody')
                            @foreach($vistorias as $vistoria)
                                <tr>
                                    <td>{{$vistoria->tipos->name}}</td>
                                    <td>{{$vistoria->Fiscal->name ?? ''}}</td>
                                    <td>{{$vistoria->cod_pi ? $vistoria->cod_pi  : $vistoria->Predio->codigo}}</td>
                                    <td><span>{{str_replace('-',"",$vistoria->dt_vistoria)}}</span>{{date('d/m/y',strtotime($vistoria->dt_vistoria))}}</td>
                                    <td>{{ !empty($vistoria->listaEnvioVistoria[0]->lista->mes) ? $vistoria->listaEnvioVistoria[0]->lista->mes : 'Não Enviado'}}</td>
                                    <td>{{$vistoria->status}} </td>
                                    <td>
                                        <img src="{{asset("paper")}}/img/icons/view.png"
                                            onclick="window.location='{{ route('multiplas.edit', ['id' => $vistoria['id']]) }}'"
                                            width="30px">
                                        @if($vistoria->status == 'cadastro')
                                            <img src="{{asset("paper")}}/img/icons/excluir.png"
                                                onclick="window.location='{{ route('multiplas.excluir', ['id' => $vistoria['id']]) }}'"
                                                width="20px">
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection


@push('scripts')
    <script type="text/javascript">
        $('.vistorias').DataTable({
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
                },
                "order": [[ 3, "desc" ]]
            }
        });
        
    </script>
@endpush
