@extends('layouts.app', [
'class' => '',
'elementActive' => 'usuarios'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Usuários</li>
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
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Cadastro de Usuários</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="{{ route('usuarios.store') }}" class="btn btn-sm btn-primary">Cadastrar</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <table class="table usuarios" id="table">
                            <thead class=" text-primary">
                                <th>
                                    Nome
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Grupo
                                </th>
                                <th>
                                    Situação
                                </th>
                                <th>
                                    Ações
                                </th>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)
                                    <tr>
                                        <td>
                                            {{ $usuario['name'] }}
                                        </td>
                                        <td>
                                            {{ $usuario['email'] }}
                                        </td>
                                        <td>
                                            {{ucfirst($usuario['grupo']) }}
                                        </td>
                                        <td>
                                            <span><strong>{{ $usuario['ativo'] == 1 ? 'Ativo' : 'Inativo' }}</strong></span>
                                        </td>
                                        <td>

                                            @php
                                                if($usuario['ativo'] == 0){

                                                echo "  <a href='#'>
                                                <img src='{{asset('paper')}}/img/icons/edit.png' width='30px'  onclick='deleteUser($usuario[id])'>
                                            </a> &nbsp;&nbsp;";

                                                echo "<button type='button' class='btn btn-danger'
                                                onclick='ativarUser($usuario[id])'>Ativar</button>";
                                            }else{
                                            @endphp

                                            <a href="#">
                                                <img src="{{asset("paper")}}/img/icons/edit.png" width="30px" onclick="window.location='{{route('usuarios.store', ['id' => $usuario['id']])}}'">
                                            </a>

                                            @php
                                                 echo "<button type='button' class='btn btn-warning'
                                                 onclick='desativarUser($usuario[id])'>Inativar</button> ";
                                            }
                                            @endphp
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
    {{-- //  window.location='{{ route('usuario.delete',['id' => $usuario['id']]) }} --}}
    @push('scripts')
        <script type="text/javascript">
            $('.usuarios').DataTable({
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


            function desativarUser(id) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.

                var confirmacao = confirm("O usuário será Desativado, tem certeza que deseja continuar com a ação ?");

                if (confirmacao) {
                    window.location = '/usuarios/desativar/' + id;
                }

            }


            function deleteUser(id) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.

                var confirmacao = confirm("O usuário será Desativado, tem certeza que deseja continuar com a ação ?");

                if (confirmacao) {
                    window.location = '/usuarios/delete/' + id;
                }

            }


            function ativarUser(id) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.

                var confirmacao = confirm("O usuário será Ativado, tem certeza que deseja continuar com a ação ?");

                if (confirmacao) {
                    window.location = '/usuarios/ativar/' + id;
                }

            }

        </script>
    @endpush
