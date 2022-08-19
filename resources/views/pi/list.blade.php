@extends('layouts.app', [
'class' => '',
'elementActive' => 'pi'
])
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item active" aria-current="page">PI</li>
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

                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Processo de Intervenção</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('pi.store') }}" class="btn btn-sm btn-primary">Cadastrar</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table pi" id="table">
                            <thead class=" text-primary">
                                <th>
                                    Código PI
                                </th>
                                <th>
                                    Prédio
                                </th>
                                <th>
                                    Empreiteira
                                </th>
                                <th>
                                    Ações
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($pis as $pi)
                                    <tr>
                                        <td>
                                            {{ $pi['codigo'] }}
                                        </td>
                                        <td>
                                            {{ $pi['predios'][0]['name'] }}
                                        </td>
                                        <td>
                                            {{ $pi['empreiteiras'][0]['name'] ?? ''}}
                                        </td>
                                        <td>
                                            <a href="#">
                                                <img src="{{asset("paper")}}/img/icons/edit.png"  onclick="window.location='{{ route('pi.store', ['id' => $pi['id']]) }}'"  width="30px">
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            $('.pi').DataTable({
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
                }
            });

            function deletePi(id) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.
                var confirmacao = confirm("A PI será desativada, deseja prosseguir ?");
                if (confirmacao) {
                    window.location = '/pi/delete/' + id;
                }
            }

        </script>
    @endpush
