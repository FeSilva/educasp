@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistoriaMultiplas'
])


@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Vistoria</a></li>
            <li class="breadcrumb-item active"
                aria-current="page">{{ empty($pi['codigo']) ? 'Cadastro de Vistoria' : 'Editar Cadastro de Vistoria' }}</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent
    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Cadastro de Vistorias Multiplas',
            ])

                @slot('body')
                    @component('components._form',[
                        'method' => 'POST',
                        'action' => route('multiplas.create'),
                        'attributes' => 'enctype=multipart/form-data accept=application/pdf'
                    ])
                        @slot('slot')
                            <div class="col-md-12">
                                <div class="row">
                                    @component('components._select',[
                                        'size' => '4',
                                        'label' => 'Tipo Vistoria: ',
                                        'name' => 'tipo_vistoria',
                                        'id' => 'tipo_vistoria'
                                    ])
                                        @slot('options')
                                            <option>Selecione</option>
                                            @foreach($tipos as $tipo)
                                                <option value="{{$tipo->vistoria_tipo_id}}">{{$tipo->name}}</option>
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                    <div class="col-md-4" id="orcamento_id" style="display:none;">
                                        <div class="form-group">
                                            <label class="col-md- col-form-label">Orçamento <span
                                                        style="color:red;">*</span></label>
                                            <input type="text" class="form-control" name="orcamento" id="orcamento" value="{{old('orcamento')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="col-md- col-form-label">Data Vistoria <span
                                                        style="color:red;">*</span></label>
                                            <input type="date" class="form-control" name="dt_vistoria" id="dt_vistoria"
                                                   value="{{old('dt_vistoria')}}">
                                        </div>

                                    </div>
                                </div>

                                <div id="cadastroVistoria" style="display:none;">
                                    <div class="row" >
                                        @component('components._input', [
                                             'size' => '4',
                                             'type' => 'text',
                                             'label' => 'Código PI:',
                                             'name' => 'codigo_pi',
                                             'id' => 'codigo_pi',
                                             'value' => old('codigo_pi'),
                                             'attributes' => 'readonly'
                                         ])
                                        @endcomponent

                                        <div class="col-md-4 col-4" id="colum_file">
                                            <label  class="col-md- col-form-label" id="file_label">Arquivo:</label>
                                            <div class="form-group">
                                            <input type="file" name="file" id="file" class="form-control">
                                            </div>
                                        </div>
                               
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label  class="col-md- col-form-label select2" id="cod_fiscal_label">Fiscal:</label>
                                                <select name="cod_fiscal_user_id" id="cod_fiscal_user_id" class="form-control">
                                                    <option>Selecione</option>
                                                    @foreach($fiscais as $fiscal)
                                                        <option value="{{$fiscal->id}}">{{$fiscal->cod_user_fde}}
                                                            - {{$fiscal->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="inputProgramas">
                                            <label class="col-md- col-form-label">Programa </label>
                                            <div class="form-group">
                                                <input list="sel-opts" id="new-opt" class="form-control" name="programa"
                                                        value="" />
                                                <small><span id="add-btn" class="btn btn-success" disabled>Adicionar +</span></small>
                                                <datalist id="sel-opts">
                                                    @foreach ($programas as $key => $programa)

                                                        <option value="{{ $programa }}">{{ $programa }}</option>
                                                    @endforeach
                                                </datalist>
                                            </div>
                                            <small><span id="msg_return"></span></small>
                                        </div>
                                    </div>
           
                                    <div class="row">

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label  class="col-md- col-form-label" id="cod_fiscal_label">Código Prédio:</label>
                                                        <input type="text" name="codigo_predio" id="codigo_predio" value="{{old('codigo_predio')}}" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label  class="col-md- col-form-label" id="cod_fiscal_label">Nome:</label>
                                                        <input type="text" name="name_predio" id="name_predio" value="{{old('name_predio')}}" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label  class="col-md- col-form-label" id="cod_fiscal_label">Diretoria:</label>
                                                        <input type="text" name="diretoria" id="diretoria" value="{{old('diretoria')}}"  class="form-control" readonly>
                                                    </div>
                                                </div>


                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" id="gs_avanco_inicial">
                                            <div class="form-group">
                                                <label class="col-md- col-form-label">Antes da Obra </label>
                                                <input type="radio" name="check_avanco" class="form-control" id="check_avanco" value="inicial"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="gs_avanco_intermediario">
                                            <div class="form-group">
                                            <label class="col-md- col-form-label">Durante a Obra </label>
                                            <input type="radio" name="check_avanco" class="form-control" id="check_avanco"  value="intermediario"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4" id="gs_avanco_final">
                                            <div class="form-group">
                                                <label class="col-md- col-form-label">Pós Obra </label>
                                                <input type="radio" name="check_avanco" class="form-control" id="check_avanco" value="final"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="submite">
                                            @component('components.buttons._submit')@endcomponent
                                        </div>
                                    </div>
                                </div>


                                @endslot
                                @endcomponent
                                @endslot
                                @endcomponent
                                @endslot
                                @endcomponent
                                @endsection

                                @push('scripts')
                                    <script text="text/javascript">
                                        $(document).ready(function () {

                                            //MASK
                                            $('#codigo_pi').mask('0000/00000');
                                            $('#codigo_predio').mask('00.00.000');
                                            $(".gs_avanco").hide();
                                            
                                        });

                                        $("#codigo_pi").change(function (e) {
                                            //function carrega predio.
                                            if (e.target.value != undefined) {
                                                chamarAjaxCodigoPI();
                                            }
                                        });

                                        function chamarAjaxCodigoPI() {
                                            if ($("#tipo_vistoria").val() == 7 || $("#tipo_vistoria").val() == 8) {
                                                $.ajax({
                                                    
                                                    headers: {
                                                        'X-CSRF-Token': $('input[name="_token"]').val()
                                                    },
                                                    type: 'POST',
                                                    url: "/carrega/pi",
                                                    data: 'codigoPi=' + $('#codigo_pi').val(),
                                                    success: function (data) {

                                                        var json = $.parseJSON(data);
                                                        json.vistorias.sort().reverse();



                                                        $("#diretoria").val(json.predios[0].diretoria);
                                                        $("#name_predio").val(json.predios[0].name);
                                                        $('#codigo_predio').val(json.predios[0].codigo)
                                                    },
                                                    error: function () {
                                                        alert('Por favor informe um código de processo de intervenção valido !');
                                                    }
                                                });
                                            } else {
                                                $("#diretoria").val("");
                                                $("#name_predio").val("");
                                                $('#codigo_predio').val("")
                                                return;
                                            }
                                        }

                                        $("#tipo_vistoria").change(function () {
                                            switch ($(this).val()) {
                                                case '4':
                                                    $("#orcamento_id").show();
                                                    $("#cadastroVistoria").show();
                                                    $("#cod_fiscal_user_id").show();
                                                    $("#cod_fiscal_label").show();
                                                    $("#inputProgramas").show();
                                                    $("#gs_avanco_inicial").hide();
                                                    $("#gs_avanco_intermediario").hide();
                                                    $("#gs_avanco_final").hide();

                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();

                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '5':
                                                    $("#orcamento_id").show();
                                                    $("#cadastroVistoria").show();
                                                    $("#cod_fiscal_user_id").show();
                                                    $("#cod_fiscal_label").show();
                                                    $("#inputProgramas").show();
                                                    $("#gs_avanco_inicial").hide();
                                                    $("#gs_avanco_intermediario").hide();
                                                    $("#gs_avanco_final").hide();


                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();

                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '6':
                                                    $("#orcamento_id").show();
                                                    $("#cadastroVistoria").show();
                                                    $("#inputProgramas").hide();
                                                    $("#cod_fiscal_user_id").show();
                                                    $("#cod_fiscal_label").show();
                                                    $("#gs_avanco_inicial").hide();
                                                    $("#gs_avanco_intermediario").hide();
                                                    $("#gs_avanco_final").hide();


                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();

                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '7':
                                                    $("#orcamento_id").hide();
                                                    $("#cadastroVistoria").show();
                                                    $("#inputProgramas").hide();
                                                    $("#cod_fiscal_user_id").show();
                                                    $("#cod_fiscal_label").show();
                                    
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#gs_avanco_inicial").show();
                                                    $("#gs_avanco_intermediario").show();
                                                    $("#gs_avanco_final").show();

                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();

                                                    $("#codigo_pi").removeAttr('readonly');
                                                    chamarAjaxCodigoPI();
                                                    break;
                                                case '8':
                                                    $("#orcamento_id").hide();
                                                    $("#cadastroVistoria").show();
                                                    $("#inputProgramas").hide();
                                                    $("#cod_fiscal_user_id").show();
                                                    $("#cod_fiscal_label").show();
                                      
                                                    $("#gs_avanco_inicial").hide();
                                                    $("#gs_avanco_intermediario").hide();
                                                    $("#gs_avanco_final").hide();

                                                    $("#colum_file").show();
                                                    $("#file_label").show();
                                                    $("#file").show();
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    chamarAjaxCodigoPI();
                                                    break;
                                            }
                                        });


                                        $("#codigo_predio").change(function(){
                                            $.ajax({
                                                headers: {
                                                    'X-CSRF-Token': $('input[name="_token"]').val()
                                                },
                                                type: 'POST',
                                                url: "/carrega/predio",
                                                data: 'codigoPredio='+ $(this).val(),
                                                success: function (data) {
                                                    var json = $.parseJSON(data);
                                                    $("#diretoria").val(json.diretoria);
                                                    $("#name_predio").val(json.name);

                                                },
                                                error: function(error){
                                                    alert(error);
                                                }
                                            });

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
