@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistoriaMultiplas'
])


@php
    $readOnlyOrcamento = '';


if($vistoriaMultipla->tipo_id != '4'and $vistoriaMultipla->tipo_id != '5' and $vistoriaMultipla->tipo_id != '6' and $vistoriaMultipla->tipo_id != '7'){
    $readOnlyOrcamento = 'readonly';
}
@endphp
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
                            <input type="hidden" name="id" id="id" value="{{$_GET['id']}}" />
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
                                                @if($vistoriaMultipla->Tipos->vistoria_tipo_id == $tipo->vistoria_tipo_id)
                                                <option value="{{$tipo->vistoria_tipo_id}}" selected>{{$tipo->name}}</option>
                                                @else
                                                    <option value="{{$tipo->vistoria_tipo_id}}">{{$tipo->name}}</option>
                                                @endif
                                            @endforeach
                                        @endslot
                                    @endcomponent
                                    <div class="col-md-4" id="orcamento_id">
                                        <div class="form-group">
                                            <label class="col-md- col-form-label">Orçamento
                                                @if(!$readOnlyOrcamento)
                                                    <span
                                                        style="color:red;">*</span>
                                                @endif
                                            </label>
                                            <input type="text" class="form-control" name="orcamento" id="orcamento"
                                                   value="{{old('orcamento',$vistoriaMultipla->num_orcamento)}}" {{$readOnlyOrcamento}}>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="col-md- col-form-label">Data Vistoria <span
                                                        style="color:red;">*</span></label>
                                            <input type="date" class="form-control" name="dt_vistoria" id="dt_vistoria"
                                                   value="{{old('dt_vistoria',$vistoriaMultipla->dt_vistoria)}}">
                                        </div>

                                    </div>
                                </div>

                                <div id="cadastroVistoria" style="">
                                    @php
                                        $readOnly = '';
                                        $redOnlypi = '';
;                                        if(!empty($vistoriaMultipla->Pi)){
                                            $redOnlypi = '';
                                            $readOnly = 'readonly';
                                        }
                                    @endphp
                                    <div class="row" >
                                        @component('components._input', [
                                             'size' => '4',
                                             'type' => 'text',
                                             'label' => 'Código PI:',
                                             'name' => 'codigo_pi',
                                             'id' => 'codigo_pi',
                                             'value' => old('codigo_pi',$vistoriaMultipla->cod_pi),
                                             'attributes' => $redOnlypi
                                         ])
                                        @endcomponent
                                            <div class="col-md-4" id="colum_file">
                                                <div class="form-group">
                                                    <label  class="col-md- col-form-label" id="file_label">Arquivo:</label><br>
                                                   <a href="../../storage/{{$vistoriaMultipla->arquivo}}" target="_blank">{{$vistoriaMultipla->name_archive}}</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="gs_avanco_inicial">
                                                <div class="form-group">
                                                    <label class="col-md- col-form-label">Inicial </label>
                                                    <input type="radio" name="check_avanco" class="form-control" id="check_avanco" value="inicial"  {{$vistoriaMultipla->check_avanco == 'inicial'? 'checked' : ''}}/>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="gs_avanco_intermediario">
                                                <div class="form-group">
                                                    <label class="col-md- col-form-label">Intermediária </label>
                                                    <input type="radio" name="check_avanco" class="form-control" id="check_avanco"  value="intermediario" {{$vistoriaMultipla->check_avanco == 'intermediario'? 'checked' : ''}}/>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="gs_avanco_final">
                                                <div class="form-group">
                                                    <label class="col-md- col-form-label">Final </label>
                                                    <input type="radio" name="check_avanco" class="form-control" id="check_avanco" value="final" {{$vistoriaMultipla->check_avanco == 'final'? 'checked' : ''}}/>
                                                </div>
                                            </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label  class="col-md- col-form-label" id="cod_fiscal_label">Fiscal:</label>
                                                <select name="cod_fiscal_user_id" id="cod_fiscal_user_id" class="form-control">
                                                    <option>Selecione</option>
                                                    @foreach($fiscais as $fiscal)
                                                        @if($vistoriaMultipla->fiscal_user_id == $fiscal->id)
                                                        <option value="{{$fiscal->id}}" selected>{{$fiscal->cod_user_fde}}
                                                            - {{$fiscal->name}}</option>
                                                        @else
                                                            <option value="{{$fiscal->id}}">{{$fiscal->cod_user_fde}}
                                                                - {{$fiscal->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label  class="col-md- col-form-label" id="cod_fiscal_label">Código Prédio:</label>
                                                    <input type="text" name="codigo_predio" id="codigo_predio" value="{{old('codigo_predio',$vistoriaMultipla->Predio->codigo)}}" class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label  class="col-md- col-form-label" id="cod_fiscal_label">Nome:</label>
                                                    <input type="text" name="name_predio" id="name_predio" value="{{old('name_predio',$vistoriaMultipla->Predio->name)}}" class="form-control" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label  class="col-md- col-form-label" id="cod_fiscal_label">Diretoria:</label>
                                                    <input type="text" name="diretoria" id="diretoria" value="{{old('diretoria',$vistoriaMultipla->Predio->diretoria)}}"  class="form-control" readonly>
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
                                            var tipo_vistoria = $("#tipo_vistoria").val();

                                                switch (tipo_vistoria) {
                                                    case '4':
                                                        $("#orcamento_id").show();
                                                        $("#cadastroVistoria").show();
                                                        $("#cod_fiscal_user_id").show();
                                                        $("#cod_fiscal_label").show();
                                                        $("#codigo_predio").removeAttr('readonly');
                                                        $("#codigo_pi").removeAttr('readonly');
                                                        $("#colum_file").hide();
                                                        $("#file_label").hide();
                                                        $("#file").hide();

                                                        $("#gs_avanco_inicial").hide();
                                                        $("#gs_avanco_intermediario").hide();
                                                        $("#gs_avanco_final").hide();

                                                        break;
                                                    case '5':
                                                        $("#orcamento_id").show();
                                                        $("#cadastroVistoria").show();
                                                        $("#cod_fiscal_user_id").show();
                                                        $("#cod_fiscal_label").show();
                                                        $("#codigo_predio").removeAttr('readonly');
                                                        $("#codigo_pi").removeAttr('readonly');
                                                        $("#colum_file").hide();
                                                        $("#file_label").hide();
                                                        $("#file").hide();
                                                        $("#gs_avanco_inicial").hide();
                                                        $("#gs_avanco_intermediario").hide();
                                                        $("#gs_avanco_final").hide();

                                                        break;
                                                    case '6':
                                                        $("#orcamento_id").show();
                                                        $("#cadastroVistoria").show();
                                                        $("#cod_fiscal_user_id").show();
                                                        $("#cod_fiscal_label").show();
                                                        $("#codigo_predio").removeAttr('readonly');
                                                        $("#codigo_pi").removeAttr('readonly');
                                                        $("#colum_file").hide();
                                                        $("#file_label").hide();
                                                        $("#file").hide();
                                                        $("#gs_avanco_inicial").hide();
                                                        $("#gs_avanco_intermediario").hide();
                                                        $("#gs_avanco_final").hide();

                                                        break;
                                                    case '7':
                                                        $("#orcamento_id").hide();
                                                        $("#cadastroVistoria").show();
                                                        $("#cod_fiscal_user_id").hide();
                                                        $("#cod_fiscal_label").hide();
                                                        $("#codigo_predio").removeAttr('readonly');
                                                        $("#codigo_pi").removeAttr('readonly');
                                                        $("#colum_file").hide();
                                                        $("#file_label").hide();
                                                        $("#file").hide();
                                                        $("#gs_avanco_inicial").show();
                                                        $("#gs_avanco_intermediario").show();
                                                        $("#gs_avanco_final").show();
                                                        break;
                                                    case '8':
                                                        $("#orcamento_id").hide();
                                                        $("#cadastroVistoria").show();
                                                        $("#cod_fiscal_user_id").hide();
                                                        $("#cod_fiscal_label").hide();
                                                        $("#codigo_predio").removeAttr('readonly');
                                                        $("#codigo_pi").removeAttr('readonly');
                                                        $("#gs_avanco_inicial").hide();
                                                        $("#gs_avanco_intermediario").hide();
                                                        $("#gs_avanco_final").hide();

                                                        break;
                                                }


                                        });



                                        $("#tipo_vistoria").change(function () {
                                            switch ($(this).val()) {
                                                case '4':
                                                    $("#orcamento_id").show();
                                                    $("#cadastroVistoria").show();

                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '5':
                                                    $("#orcamento_id").show();
                                                    $("#cadastroVistoria").show();

                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '6':
                                                    $("#orcamento_id").show();
                                                    $("#cadastroVistoria").show();

                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '7':
                                                    $("#orcamento_id").hide();
                                                    $("#cadastroVistoria").show();

                                                    $("#colum_file").hide();
                                                    $("#file_label").hide();
                                                    $("#file").hide();
                                                    $("#cod_fiscal_user_id").attr('attributes','readonly');
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
                                                    break;
                                                case '8':
                                                    $("#orcamento_id").hide();
                                                    $("#cadastroVistoria").show();
                                                    $("#cod_fiscal_user_id").attr('attributes','readonly');
                                                    $("#codigo_predio").removeAttr('readonly');
                                                    $("#codigo_pi").removeAttr('readonly');
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
                                                    $("#codigo_pi").attr('disabled',true);
                                                },
                                                error: function(error){
                                                    alert(error);
                                                }
                                            });

                                        });
                                    </script>
                            @endpush
