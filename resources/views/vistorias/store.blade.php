@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item"><a href="{{ Route("predios.list") }}">Prédios</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ empty($predios['codigo']) ? 'Cadastro de Prédios' : 'Editar Cadastro de Prédio' }}</li>
        </ol>
    </nav>

    @if (session()->has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Sucesso:</b> {{ session('success') }}</span>
            </div>
        </div>

    @endif


    @if (session()->has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span><b> Atenção !:</b> {{ session('error') }}</span>
            </div>
        </div>
    @endif


    <div class="content">
        <div class="row">
            <form class="col-md-12" action="" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                <input type="hidden" name="id_user" id="id_user" value="{{ Auth::id() }}" />
                <input type="hidden" name="id" id="id" value="">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Código Pi <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="codigo" id="codigo"
                                           class="form-control"
                                           value="">

                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Código Prédio <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <!-- Código -->

                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Nome do Prédio <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <!-- Nome do Predio -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Diretoria<span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <!-- Diretoria -->

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h5 class="title">
                        </h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Tipo de Vistoria<span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="tipoVistoria" id="tipoVistoria"
                                           class="form-control"
                                           value="">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Data de Abertura <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="date" name="dt_abertura_pi" id="dt_abertura_pi"
                                           class="form-control"
                                           value="">

                                </div><div class="form-group">
                                    <!-- Nome do Predio -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Anexo da Folha de abertura<span style="color:red;">*</span></label>

                                <div class="form-group">

                                    <input type="file" class="form-control-file" id="exampleFormControlFile1" style="background-color: #5e5e5e;">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            //MASK
            $('#codigo').mask('00.00.000');
            $('#telefone').mask('(00) 00000-0000');
        });

    </script>
@endpush
