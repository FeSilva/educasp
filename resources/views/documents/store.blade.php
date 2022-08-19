@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'os'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Documentos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cadastrar Documento OS</li>
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
                                <h3 class="mb-0">Cadastrar Documento OS</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('documents.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-md- col-form-label">Cód. PI <span style="color:red;">*</span></label>
                                    <div class="form-group">
                                        <input type="text" name="codigo" id="codigo"
                                            class="form-control @error('codigo') is-invalid @enderror" placeholder=""
                                            value="{{ old('codigo') }}">
    
                                        @error('codigo')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                            
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer ">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-info btn-round">Gerar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection