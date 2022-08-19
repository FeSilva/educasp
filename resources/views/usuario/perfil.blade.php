@extends('layouts.app', [
'class' => '',
'elementActive' => 'profile'
])

@php


    $grupos = [ //Criar Model pra grupo de usuários
        'analista' => 'Analista',
        'fiscal' => 'Fiscal',
        'gestor' => 'Gestor',
        'gestao_social' => 'Gestão Social',
        'operacional_tecnico' =>'Operacional Técnico'
    ];

$readonly = 'disabled';

if (auth()->user()->grupo == 'gestor' || auth()->user()->grupo == 'analista') {
$readonly = '';
}

@endphp

@section('content')

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Configurações</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perfil</li>
        </ol>
    </nav>

    <div class="content">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        @if (session('password_status'))
            <div class="alert alert-success" role="alert">
                {{ session('password_status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-4">
                <div class="card card-user">
                    <div class="image">
                    </div>
                    <div class="card-body">
                        <div class="author">
                            <a href="#">


                                <h5 class="title">{{ __(auth()->user()->name) }}</h5>
                            </a>
                            <p class="description">
                                @ {{ __(auth()->user()->name) }}
                            </p>
                        </div>
                        <p class="description text-center">
                            {{ __(auth()->user()->obs) }}
                        </p>
                    </div>
                    {{-- <div class="card-footer">
                        <hr>
                         <div class="button-container">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-6 ml-auto">
                                    <h5>{{ __('12') }}
                                        <br>
                                        <small>{{ __('Files') }}</small>
                                    </h5>
                                </div>
                                <div class="col-lg-4 col-md-6 col-6 ml-auto mr-auto">
                                    <h5>{{ __('2GB') }}
                                        <br>
                                        <small>{{ __('Used') }}</small>
                                    </h5>
                                </div>
                                <div class="col-lg-3 mr-auto">
                                    <h5>{{ __('24,6$') }}
                                        <br>
                                        <small>{{ __('Spent') }}</small>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>


                {{-- <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Equipe '.ucfirst(auth()->user()->grupo)) }}</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled team-members">

                            <li>
                                <div class="row">
                                    <div class="col-md-2 col-2">
                                        <div class="avatar">
                                            <img src="{{ asset('paper/img/faces/ayo-ogunseinde-2.jpg') }}" alt="Circle Image"
                                                class="img-circle img-no-padding img-responsive">
                                        </div>
                                    </div>
                                    <div class="col-md-7 col-7">
                                        {{ __('') }}
                                        <br />
                                        <span class="text-muted">
                                            <small>{{ __('Offline') }}</small>
                                        </span>
                                    </div>
                                    <div class="col-md-3 col-3 text-right">
                                        <button class="btn btn-sm btn-outline-success btn-round btn-icon"><i
                                                class="fa fa-envelope"></i></button>
                                    </div>
                                </div>
                            </li>


                        </ul>
                    </div>
                </div> --}}
            </div>
            <div class="col-md-8 text-center">
                <form class="col-md-12" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="title">{{ __('Editar Perfil') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Name') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Name"
                                            value="{{ auth()->user()->name }}" required>
                                    </div>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Email') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email"
                                            value="{{ auth()->user()->email }}" required>
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Grupo') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        {{ Form::select('grupo', $grupos, auth()->user()->grupo, ['id' => 'grupo', 'class' => 'form-control', 'placeholder' => 'Selecione',$readonly]) }}
                                    </div>
                                    @if ($errors->has('grupo'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('grupo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Celular') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" name="celular" id="celular" class="form-control"
                                            placeholder="celular" value="{{ auth()->user()->celular }}" required>
                                    </div>
                                    @if ($errors->has('celular'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('celular') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Descrição') }}</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" id="obs" name="obs"
                                        rows="3">{{ auth()->user()->obs }}</textarea>
                                    @if ($errors->has('celular_profissional'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('obs') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info btn-round">{{ __('Salvar') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="col-md-12" action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="title">{{ __('Redefinição de Senha') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Senha atual') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="old_password" class="form-control"
                                            placeholder="Senha Atual" required>
                                    </div>
                                    @if ($errors->has('old_password'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('old_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Nova senha') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" placeholder="senha"
                                            required>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-3 col-form-label">{{ __('Verificação de senha') }}</label>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Verificação de senha" required>
                                    </div>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info btn-round">{{ __('Salvar') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {


            //MASK
            $('#celular').mask('(00) 00000-0000');
            $('#celular_profissional').mask('(00) 00000-0000');

        });

    </script>
@endpush
