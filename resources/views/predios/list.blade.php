@extends('layouts.app', [
'class' => '',
'elementActive' => 'predios'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Prédios</li>
        </ol>
    </nav>
    @if (session()->has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Sucesso </b> {{ session('success') }}</span>
            </div>
        </div>

    @endif


    @if (session()->has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Atenção ! </b> {{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Prédios</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('predios.store') }}" class="btn btn-sm btn-primary">Cadastrar</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <table class="table predios" id="predios">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>
                                            Código
                                        </th>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Diretoria
                                        </th>
                                        <th>
                                            Municipio
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {




            //definir mascara pra prazo_total



                $('.predios').DataTable({
                    processing: false,
                    serverSide: true,
                    ajax: "{{ route('predios.list') }}",
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
                            data: 'codigo',
                            name: 'codigo'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'diretoria',
                            name: 'diretoria'
                        },
                        {
                            data: 'municipio',
                            name: 'municipio'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });
            });

            function deletePredios(id) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.
                var confirmacao = confirm("O prédio será desativado, deseja prosseguir ?");

                if (confirmacao) {
                    window.location = '/predios/delete/' + id;
                }
            }

        </script>
    @endpush
