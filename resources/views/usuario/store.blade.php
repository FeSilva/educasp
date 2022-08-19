@extends('layouts.app', [
'class' => '',
'elementActive' => 'usuarios'
])

@php

    $readonly ="disabled";

    if(!$usuarios['id'] || auth()->user()->grupo == 'gestor' || auth()->user()->grupo == 'analista'){
        $readonly = '';
    }

@endphp
@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item"><a href="{{ route('usuarios.list') }}">Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ empty($usuarios['id']) ? 'Cadastro de Usuários' : 'Editar Cadastro de Usuários' }}</li>
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
            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <form class="col-md-12" action="{{ route('usuarios.create') }}" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                <div class="card">
                    <div class="card-header">
                    
                        <div class="col-md-8">
                            <span class="badge me-1 rounded-pill bg-primary">{{ $usuarios['cod_user_fde'] }}</span>
                            <span class="badge me-1 rounded-pill bg-danger">{{ strtoupper($usuarios['grupo'])  }}</span>                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" id="id"
                                value="{{ empty($usuarios['id']) ? '' : $usuarios['id'] }}">
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Nome <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Nome"
                                        value="{{ old('name', $usuarios['name']) }}">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">E-mail <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                        value="{{ old('email', $usuarios['email']) }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">


                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Grupo de Usuário <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    {{ Form::select('grupoUser', $grupos, old('grupoUser', $usuarios['grupo']), ['id' => 'grupoUser', 'class' => 'form-control', 'placeholder' => 'Selecione',$readonly]) }}

                                    @error('grupoUser')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Celular </label>

                                <div class="form-group">
                                    <input type="text" name="celular" id="celular" class="form-control" placeholder="(11) xxxx-xxxx" value="{{ old('celular', $usuarios['celular']) }}">


                                </div>

                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md- col-form-label">Observações </label>

                                <div class="form-group">
                                    <textarea class="form-control" id="observacoes" name="observacoes"
                                              rows="3">{{ old('observacoes', $usuarios['observacoes']) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Status </label>

                                <div class="form-group">
                                    {{ Form::select('status', $status, old('status', $usuarios['ativo']), ['id' => 'status', 'class' => 'form-control', 'placeholder' => 'Selecione',$readonly]) }}

                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Cód. FDE </label>

                                <div class="form-group">

                                        <input type="text" name="cod_user_fde" id="cod_user_fde" class="form-control" placeholder="" value="{{ old('cod_user_fde', $usuarios['cod_user_fde']) }}">
                                </div>
                            </div>
                        </div>

                        @if (!$usuarios['id'] || Auth()->user()->grupo == 'gestor' || Auth()->user()->grupo == 'analista')
                            @php $disabled=""; @endphp
                            @if($usuarios['id'] and (Auth()->user()->grupo == 'gestor' || Auth()->user()->grupo == 'analista'))
                                @php $disabled="disabled"; @endphp
                                <div class='row'>
                                    <div class='col-md-12'>
                                        <p> <a href='#' onclick='alterarSenha()' id="alterPass"></a></p>
                                    </div>
                                </div>
                            @endif



                            <div class="row">
                                <div class="col-md-6" >
                                    <label class="col-md- col-form-label">senha <span style="color:red;">*</span></label>
                                    <div class="form-group">
                                        <input type="password" name="password" id="password"
                                            class="form-control password @error('password') is-invalid @enderror"
                                            placeholder="Senha" {{$disabled}}>

                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-md- col-form-label">Verificação de senha </label>
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control password @error('password') is-invalid @enderror"
                                            placeholder="Verificar senha" {{$disabled}}>

                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
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
            $("#alterPass").html(" - Alterar senha do usuário.");
            //MASK
            $('#celular').mask('(00) 00000-0000');
            $('#celular_profissional').mask('(00) 00000-0000');
        });

        function alterarSenha() {
            $(".password").removeAttr('disabled');
            //$("#alterPass").html(" - Desabilitar campo de senhar.");
        }
    </script>
@endpush
