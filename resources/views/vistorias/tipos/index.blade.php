@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistoriasTipos'
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
            <li class="breadcrumb-item active" aria-current="page">Vistorias Tipos</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Cadastro de Tipos Vistorias',
                'route' => route('vistorias.tipo.create'),
            ])
                @slot('body')
                         
                    @component('components._table', ['class' => 'vistoriasTipos'])
                        @slot('thead')
                            <th>Tipo Vistorias</th>
                            <th>Sigla</th>
                            <th>Valor a Receber</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th align="center">Ações</th>
                        @endslot
                        @slot('tbody')
                            @foreach($tipos as $tipo)
                                @php 
                                    $status = $tipo->status == 1 ? 'Ativo' : 'Inativo';
                                @endphp
                                <tr>
                                    <td>{{ $tipo->name }}</td>
                                    <td>{{ $tipo->sigla }}</td>
                                    <td>{{ number_format($tipo->price, 2, ",",".") }}</td>
                                    <td>{{ $tipo->amount_to_receive }}</td>
                                    <td>{{ $status }}</td>
                                    <td>
                                        <a href="{{ route('vistorias.tipo.edit', ['tipo_id' => $tipo->vistoria_tipo_id]) }}" alt="editar tipo de vistoria">
                                            <img src="{{asset("paper")}}/img/icons/edit.png"  width="30px">
                                        </a>
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

        $('.vistoriasTipos').DataTable({
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
