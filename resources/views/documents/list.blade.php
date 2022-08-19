@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'os'
])


@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Documentos</a></li>
            <li class="breadcrumb-item active" aria-current="page">OS</li>
        </ol>
    </nav>
    @if (session()->has('success'))
        <div class="col-md-12" id="success">
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Sucesso:</b> {{ session('success') }}</span>
            </div>
        </div>

    @endif


    @if (session()->has('error'))
        <div class="col-md-12" id="success">
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> (Atenção !) </b> {{ session('error') }}</span>
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
                                <h3 class="mb-0">Documentos OS</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('documents.create') }}" class="btn btn-sm btn-primary">Cadastrar</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table" id="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Nome da Escola</th>
                                    <th>Número da OS</th>
                                    <th>Número GS</th>
                                    <th>PI</th>
                                    <th width="100px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td>{{ $document->nome_escola }}</td>
                                        <td>{{ $document->numero_os }}</td>
                                        <td>{{ $document->numero_gestao_social }}</td>
                                        <td>{{ $document->pi }}</td>
                                        <td>
                                            <a href="#">
                                                <img src="{{asset("paper")}}/img/icons/edit.png"  onclick="openModal('{{ $document->id }}')"  width="20px">
                                            </a>
                                            <a href="{{ route('documents.show', $document->id) }}" target="_blank">
                                                <img src="{{asset("paper")}}/img/icons/view.png"  width="20px">
                                            </a>
                                            <a href="{{ route('documents.pdf', $document->id) }}" target="_blank">
                                                <img src="{{asset("paper")}}/img/icons/download.png"  width="20px">
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
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 col-sm-12">
                            <form action="#" method="post">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-md- col-form-label">Justificativa</label>
                                        <div class="form-group">
                                            <textarea name="justificativa" id="justificativa"
                                                class="form-control" placeholder=""></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" id="salvar" class="btn btn-info btn-round">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
    <script>
        $('#table').DataTable({
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

        
        function openModal(id) {
            $('.modal').modal('show');
            $('#salvar').click(function() {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    url: `/documents/os/${id}`,
                    method: 'POST',
                    data: {
                        justificativa: $('#justificativa').val()
                    },
                    success: function(data) {
                        document.location.reload(true);
                    },
                    error: function(error) {
                        document.location.reload(true);
                    },
                    complete: function() {
                        $('.modal').modal('hide');
                    }
                })
            });
        }
    </script>
@endpush