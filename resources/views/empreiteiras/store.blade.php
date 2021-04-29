@extends('layouts.app', [
'class' => '',
'elementActive' => 'empreiteiras'
])


@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
        <li class="breadcrumb-item"><a href="{{ route('empreiteiras.list') }}">Empreiteiras</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ empty($empreiteira['codigo']) ? 'Cadastro de Empreiteira' : 'Editar Cadastro de Empreiteira' }}</li>
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
            <form class="col-md-12" action="{{ route('empreiteiras.create') }}" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                <input type="hidden" name="id_user" id="id_user" value="{{ Auth::id() }}" />
                <input type="hidden" name="id" id="id" value="{{!empty($empreiteira['id']) ? $empreiteira['id'] : ''  }}" />

                <div class="card">
                    <div class="card-header">
                        <h5 class="title">{{ empty($empreiteira['codigo']) ? 'Cadastro de Empreiteira' : 'Editar Cadastro de Empreiteira' }}</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Razão Social <span style="color:red;">*</span> </label>

                                <div class="form-group">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"  placeholder="Nome Empreiteiras"
                                        value="{{ !empty($empreiteira['name']) ? $empreiteira['name'] : '' }}" >

                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Nome Fantasia </label>

                                <div class="form-group">
                                    <input type="text" name="fantasia" class="form-control @error('fantasia') is-invalid @enderror"  placeholder="Nome Fantasia"
                                        value="{{ !empty($empreiteira['fantasia']) ? $empreiteira['fantasia'] : '' }}" >

                                        @error('fantasia')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-4">
                                <label class="col-md- col-form-label">CNPJ <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="cnpj" id="cnpj" class="form-control @error('cnpj') is-invalid @enderror" placeholder="Ex.:99.999.999/9999-99"
                                        value="{{ !empty($empreiteira['cnpj']) ? $empreiteira['cnpj'] : '' }}" >
                                        @error('cnpj')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">E-mail <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                        value="{{ !empty($empreiteira['email']) ? $empreiteira['email'] : '' }}" >
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Nome do Contato: <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="nome_contato" id="nome_contato" class="form-control @error('nome_contato') is-invalid @enderror" placeholder="" value="{{ !empty($empreiteira['nome_contato']) ? $empreiteira['nome_contato'] : '' }}">
                                    @error('nome_contato')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>

                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Telefone <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="telefone" name="telefone" id="telefone" class="form-control @error('telefone') is-invalid @enderror" placeholder="Telefone"
                                        value="{{ !empty($empreiteira['telefone']) ? $empreiteira['telefone'] : '' }}" >
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
                                <label class="col-md- col-form-label">E-mail (Opcional)</label>

                                <div class="form-group">
                                    <input type="email" name="email_opcional" class="form-control @error('email_opcional') is-invalid @enderror" placeholder="Email"
                                           value="{{ !empty($empreiteira['email_opcional']) ? $empreiteira['email_opcional'] : '' }}" >
                                    @error('email_opcional')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Nome do Contato (Opcional)</label>

                                <div class="form-group">
                                    <input type="text" name="nome_contato_opcional" id="nome_contato_opcional" class="form-control @error('nome_contato_opcional') is-invalid @enderror" placeholder="" value="{{ !empty($empreiteira['nome_contato_opcional']) ? $empreiteira['nome_contato_opcional'] : '' }}">
                                    @error('nome_contato_opcional')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Telefone (Opcional):</label>

                                <div class="form-group">
                                    <input type="text" name="telefone_opcional" id="telefone_opcional" class="form-control @error('telefone_opcional') is-invalid @enderror" placeholder="Telefone"
                                           value="{{ !empty($empreiteira['telefone_opcional']) ? $empreiteira['telefone_opcional'] : '' }}" >
                                    @error('telefone_opcional')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
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

            $("#cnpj").mask("99.999.999/9999-99")
            //MASK
            $('#telefone').mask('(00) 00000-0000');
            $('#telefone_opcional').mask('(00) 00000-0000');

        });

    </script>
@endpush
