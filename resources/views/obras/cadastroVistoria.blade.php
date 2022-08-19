@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistoria-cadastro'
])


@section('content')
    <div class="content">
        <div class="row">
            {{-- @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
            <form class="col-md-12" action="{{ route('usuario.create') }}" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">{{ empty($usuarios) ? 'Cadastro de Usuários' : 'Editar Cadastro de Usuários' }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" id="id" value="{{ empty($usuarios['id']) ? '' : $usuarios['id'] }}">
                            <div class="col-md-12">
                                <label class="col-md- col-form-label">Nome</label>

                                <div class="form-group">
                                    <input type="text" name="name"  class="form-control @error('name') is-invalid @enderror" placeholder="Nome" value="{{ !empty($usuarios['name']) ? $usuarios['name'] : '' }}">
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">E-mail</label>

                                <div class="form-group">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"value="{{ !empty($usuarios['email']) ? $usuarios['email'] : '' }}">

                                    @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Grupo de Usuário</label>

                                <div class="form-group">
                                    {{  Form::select('grupoUser', $grupos, !empty($usuarios['grupo']) ? $usuarios['grupo'] : '', ['id'=>'grupoUser','class' =>'form-control','placeholder' => 'Selecione'])  }}

                                    @error('grupoUser')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Celular:</label>

                                <div class="form-group">
                                    <input type="text" name="celular" id="celular" class="form-control @error('celular') is-invalid @enderror" placeholder="(11) xxxx-xxxx"
                                        value="{{ !empty($usuarios['celular']) ? $usuarios['celular'] : '' }}">

                                        @error('celular')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                </div>

                            </div>
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Celular Profissional:</label>

                                <div class="form-group">
                                    <input type="text" name="celular_profissional" id="celular_profissional" class="form-control" placeholder="(11) xxxx-xxxx"

                                        value="{{ !empty($usuarios['celular_profissional']) ? $usuarios['celular_profissional'] : '' }}">
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md- col-form-label">Observações:</label>

                                <div class="form-group">
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ empty($usuarios['obs']) ? '' : $usuarios['obs'] }}</textarea>
                                </div>


                                {{-- @error('observacoes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror --}}

                            </div>
                        </div>

                        @if(!$usuarios)
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">senha</label>
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Senha">

                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Verificação de senha</label>
                                <div class="form-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Verificar senha">


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


            //MASK
            $('#celular').mask('(00) 00000-0000');
            $('#celular_profissional').mask('(00) 00000-0000');

        });

    </script>
@endpush
