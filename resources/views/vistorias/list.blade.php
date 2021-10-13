@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])
<style>
    .vistorias span {
        display: none;
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
                'route' => route('vistorias.store'),
            ])
                @slot('body')
                    @component('components._table', ['class' => 'vistorias'])
                        @slot('thead')
                            <th>Cód</th>
                            <th>Tipo de Vistoria</th>
                            <th>Data</th>
                            <th>%Atual</th>
                            <th align="center">Ações</th>
                        @endslot
                        @slot('tbody')
                            @foreach($vistorias as $vistoria)
                                <tr>
                                    <td>{{$vistoria->codigo}}</td>
                                    <td>{{$vistoria->tipos->name}}</td>
                                    <td>
                                        <span>{{str_replace('-',"",$vistoria->dt_vistoria)}}</span>{{date('d/m/y',strtotime($vistoria->dt_vistoria))}}
                                    </td>
                                    <td>{{empty($vistoria->avanco_fisico) ? '00,0' : $vistoria->avanco_fisico}}</td>
                                    <td>
                                        <a href="#">
                                            <img src="{{asset("paper")}}/img/icons/view.png"
                                                 onclick="window.location='{{ route('vistorias.store', ['id' => $vistoria['id']]) }}'"
                                                 width="30px">
                                        </a>
                                        @if($vistoria->status == 'cadastro')
                                            <a href="#">
                                                <img src='{{asset('paper')}}/img/icons/excluir.png' width='20px'
                                                     onclick="window.location='{{ route('vistorias.excluir', ['id' => $vistoria['id']]) }}'">
                                            </a>
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
                "order": [[3, "desc"]]
            }
        });

    </script>
@endpush
