@extends('layouts.app', [
'class' => '',
'elementActive' => 'abertura'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pi.list') }}">PI</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ empty($pi['codigo']) ? 'Cadastro de PI' : 'Editar PI' }}</li>
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
            <form class="col-md-12" action="{{ route('pi.create') }}" method="POST">
                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                <input type="hidden" name="id_predio" id="id_predio" class="form-control" placeholder=""
                       value="{{ old('id_predio', $pi['id_predio']) }}">
                <div class="card">
                    <div class="card-header">
                        <h5 class="title">{{ empty($pi['codigo']) ? 'Cadastro de PI' : 'Editar PI' }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="id" id="id" value="{{ empty($pi['id']) ? '' : $pi['id'] }}">
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Cód. PI <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="codigo" id="codigo"
                                           class="form-control @error('codigo') is-invalid @enderror" placeholder=""
                                           value="{{ old('codigo', $pi['codigo']) }}">

                                    @error('codigo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror

                                </div>
                            </div>


                        </div>
                        <div class="row">

                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Cód. Prédio <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="codigo_predio" id="codigo_predio"
                                           class="form-control @error('codigo_predio') is-invalid @enderror"
                                           placeholder="Código do Prédio"
                                           value="{{ old('codigo_predio', $pi['predios']['codigo']) }}">
                                    @error('codigo_predio')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="col-md- col-form-label">Nome do Prédio</label>

                                <div class="form-group">
                                    <input type="text" name="name_predio" id="name_predio" class="form-control "
                                           value="{{ old('name_predio', $pi['predios']['name_predio']) }}"
                                           readonly="readonly">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md- col-form-label">Diretoria:</label>

                                <div class="form-group">
                                    <input type="text" name="diretoria" id="diretoria"
                                           class="form-control @error('diretoria') is-invalid @enderror"
                                           value="{{ old('diretoria', $pi['predios']['diretoria']) }}" readonly="readonly">
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

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $("#codigo_pi").change(function() {
                    //function carrega predio.
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('input[name="_token"]').val()
                        },
                        type: 'POST',
                        url: "/carrega/pi",
                        data: 'codigoPi=' + $(this).val(),
                        success: function(data) {
                            // $(location).attr('href', "{{ URL::to(Request::path()) }}");
                            var json = $.parseJSON(data);
                            $("#id_predio").val(json.id);
                            $("#name_predio").val(json.name);
                            $("#diretoria").val(json.diretoria);

                        },
                        error: function() {
                            alert('Por favor informe um código de prédio valido.');
                        }
                    });
                });

                //MASK
                $('#codigo').mask('0000 / 00000');
                $('#codigo_predio').mask('00.00.000');
                $('#valor_total').mask('#.##0,00', {
                    reverse: true
                });

                $('#valor_item').mask('#.##0,00', {
                    reverse: true
                });
                //definir mascara pra prazo_total
            });

            function deleteItem(id, id_pi) { //Falar sobre Modal bootstrap no laravel para melhorar layout de notificação.
                var confirmacao = confirm("O item será Deletar, deseja prosseguir ?");

                if (confirmacao) {
                    window.location = '/items/delete/' + id + '/' + id_pi;
                }
            }


            var input_ele = document.getElementById('new-opt');
            var add_btn = document.getElementById('add-btn');
            var sel_opts = document.getElementById('sel-opts');
            var input_val;

            input_ele.addEventListener('change', function() {
                add_btn.removeAttribute('disabled');
            });

            add_btn.addEventListener('click', function() {
                input_val = input_ele.value;
                if (input_val.trim() != '') {
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('input[name="_token"]').val()
                        },
                        type: 'POST',
                        url: "/pi/programa",
                        data: 'programa=' + input_val + '&id=' + $('#id').val(),
                        success: function(data) {
                            $("#msg_return").html("Programa Adicionado com Sucesso");
                        },
                        error: function(error) {
                            console.log(error);
                            $("#msg_return").html("Programa já existente.")
                        }
                    });




                    /* sel_opts.innerHTML += "<option value='"+input_val+"'>" +input_val+ "</option>";
                     input_ele.value = input_val;*/
                }
            });

        </script>
    @endpush
