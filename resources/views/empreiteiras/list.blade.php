@extends('layouts.app', [
'class' => '',
'elementActive' => 'empreiteiras'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Empreiteiras</li>

        </ol>
    </nav>

    @if (session()->has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Sucesso: </b> {{ session('success') }}</span>
            </div>
        </div>

    @endif


    @if (session()->has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Atenção !: </b> {{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                {{-- <div class="alert alert-danger alert-dismissible fade show align-items-center" >
                    <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                      <i class="nc-icon nc-simple-remove"></i>
                    </button>
                    <span><b> Atenção ! - </b>{{ !empty($userDesativado) ? $userDesativo : '' }} foi desativo.</span>
                </div> --}}
                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Cadastro de Empreiteiras</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('empreiteiras.store') }}" class="btn btn-sm btn-primary">Cadastrar</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table empreiteiras">
                            <thead class=" text-primary">
                                <th>
                                    Código
                                </th>
                                <th>
                                    Razão Social
                                </th>
                                <th>
                                    E-MAIL
                                </th>
                                <th>
                                    Ações
                                </th>
                            </thead>
                            {{-- <tbody>
                                    @foreach ($empreiteiras as $empreiteira)
                                        <tr>
                                            <td>
                                                {{ $empreiteira['id'] }}
                                            </td>
                                            <td>
                                                {{ $empreiteira['name'] }}
                                            </td>
                                            <td>
                                                {{ $empreiteira['cnpj'] }}
                                            </td>
                                            <td>
                                                {{ $empreiteira['email'] }}
                                            </td>
                                            <td>
                                                {{ $empreiteira['nome_contato'] }}
                                            </td>
                                            <td>
                                                <button type="button"
                                                    onclick="window.location='{{ route('empreiteiras.store', ['id' => $empreiteira['id']]) }}'"
                                                    class="btn btn-primary">Editar</button>
                                              <button type="button" class="btn btn-warning" onclick="deleteEmpreiteiras({{ $empreiteira['id'] }})">Desativar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody> --}}
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.empreiteiras').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('empreiteiras.list') }}",
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
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: false
                },
            ]

        });

        function deleteEmpreiteiras(id) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.

            var confirmacao = confirm("A empreiteira será desativado, deseja prosseguir ?");

            if (confirmacao) {
                window.location = 'empreiteiras/delete/' + id;
            }

        }

    </script>
@endpush
