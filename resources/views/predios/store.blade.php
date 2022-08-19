@extends('layouts.app', [
'class' => '',
'elementActive' => 'predios'
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
            <form class="col-md-12" action="{{ route('predios.create') }}" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                <input type="hidden" name="id_user" id="id_user" value="{{ Auth::id() }}" />
                <input type="hidden" name="id" id="id" value="{{ empty($predios['id']) ? '' : $predios['id'] }}">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">
                            {{ empty($predios['codigo']) ? 'Cadastro de Prédios' : 'Editar Cadastro de Prédio' }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Código <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="codigo" id="codigo"
                                        class="form-control @error('codigo') is-invalid @enderror"
                                        value="{{ old('codigo', $predios['codigo']) }}">

                                    @error('codigo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Nome <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Nome"
                                        value="{{ old('name', $predios['name']) }}">

                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Diretoria de Ensino<span style="color:red;">*</span></label>
                                <div class="form-group">
                                    <select class="form-control @error('diretoria') is-invalid @enderror" id="diretoria"
                                        name="diretoria">
                                        <option>Selecione</option>
                                        @foreach ($diretorias as $diretoria)
                                            @if (isset($predios['diretoria']) && $predios['diretoria'] == $diretoria['diretoria'])
                                                <option value="{{ $diretoria['diretoria'] }}" selected>
                                                    {{ $diretoria['diretoria'] }}</option>
                                            @else
                                                <option value="{{ $diretoria['diretoria'] }}">
                                                    {{ $diretoria['diretoria'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>

                                    @error('diretoria')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Telefone </label>
                                <div class="form-group">
                                    <input type="text" name="telefone" id="telefone"
                                        class="form-control @error('telefone') is-invalid @enderror" placeholder="Telefone"
                                        value="{{ old('telefone', $predios['telefone']) }}">

                                    @error('telefone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Municipio <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="municipio" id="municipio"
                                        class="form-control @error('municipio') is-invalid @enderror"
                                        placeholder="Ex.: São Paulo"
                                        value="{{ old('municipio', $predios['municipio']) }}">

                                    @error('municipio')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Endereço <span style="color:red;">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="endereco" id="endereco"
                                        class="form-control @error('endereco') is-invalid @enderror" placeholder="Endereço"
                                        value="{{ old('endereco', $predios['endereco']) }}">

                                    @error('endereco')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Bairro <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="bairro" id="bairro"
                                        class="form-control @error('bairro') is-invalid @enderror"
                                        value="{{ old('bairro', $predios['bairro']) }}">

                                    @error('bairro')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="card-footer ">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info btn-round">Salvar</button>
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
