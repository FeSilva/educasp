@extends('layouts.app', [
'class' => '',
'elementActive' => 'company'
])

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
        <li class="breadcrumb-item active" aria-current="page">Company</li>
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
                            <h3 class="mb-0">Company</h3>
                        </div>
                        
                    </div>
                </div>
                <div class="card-body">
                    <form class="col-md-12" action="{{ route('company.update') }}" method="POST" style="overflow: auto">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                        <input type="hidden" name="company_id" value="{{ $company->company_id }}" />
                        <div class="row align-items-start">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Razão Social</label>
                                    <input type="text" class="form-control" id="razao_social" aria-describedby="emailHelp" placeholder="Razão Social" name="razao_social" value="{{ $company->razao_social }}">
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fantasia</label>
                                    <input type="text" class="form-control" id="fantasia" aria-describedby="emailHelp" placeholder="Fantasia" name="fantasia" value="{{ $company->fantasia }}">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">CNPJ</label>
                                    <input type="text" class="form-control" id="cnpj" aria-describedby="emailHelp" placeholder="CNPJ" name="cnpj" value="{{ $company->cnpj }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fiscal</label>
                                    <select class="form-control" name="user_id" id="user_id">
                                        <option>Selecione o fiscal responsável</option>
                                        @foreach ($fiscais as $fiscal)
                                            @if($company->user_id == $fiscal->id)
                                                <option value="{{ $fiscal->id }}" selected>{{ $fiscal->name }}</option>
                                            @endif
                                            <option value="{{ $fiscal->id }}">{{ $fiscal->name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-4 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Salvar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection