@extends('layouts.app', [
'class' => '',
'elementActive' => 'pi'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pi.list') }}">PI</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pi.store',['id' => $pi['id_pi']]) }}">Editar Pi</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ empty($itens['codigo']) ? 'Cadastrar Item' : 'Editar Item' }}</li>
        </ol>
    </nav>

    @if (session()->has('msg_succes')))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="nc-icon nc-simple-remove"></i>
            </button>
            <span><b> Sucesso:</b> {{ session()->get('message') }}</span>
        </div>
    @endif


    @if (session()->has('msg_error')))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="nc-icon nc-simple-remove"></i>
            </button>
            <span><b> Atenção !:</b> {{ session()->get('message') }}</span>
        </div>
    @endif

    <div class="content">
        <div class="row">
            <form class="col-md-12" action="{{ route('items.create') }}" method="POST">

                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
               <input type="hidden" name="id_pi" class="form-control" placeholder="" value="{{ !empty($pi['id_pi']) ? $pi['id_pi'] : '' }}" />
                <input type="hidden" name="id" id="id" class="form-control" placeholder="" value="{{ !empty($pi['id']) ? $pi['id'] : '' }}" />

                <div class="card">
                    <div class="card-header">
                        <h5 class="title"></h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Item PI</label>

                                <div class="form-group">
                                    <input type="text" name="item_pi"
                                        class="form-control @error('item_pi') is-invalid @enderror" placeholder=""
                                        value="{{ old('item_pi', $pi['num_item']) }}" />

                                    @error('item_pi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Tipo Item</label>

                                <div class="form-group">
                                    {{ Form::select('tipo_item', $items, old('tipo_item', $pi['tipo_item']), ['id' => 'tipo_item', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}


                                    @error('tipo_item')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Valor</label>
                                <div class="form-group">
                                    <input type="text" name="valor_item" id="valor_item" class="form-control  @error('valor_item') is-invalid @enderror" placeholder="" value="{{ old('valor_item', $pi['valor']) }}" />
                                    @error('valor_item')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">OIS/OS</label>

                                <div class="form-group">
                                    <input type="date" name="dt_assin_ois" id="dt_assin_ois"
                                        class="form-control  @error('dt_assin_ois') is-invalid @enderror"
                                        value="{{ old('dt_assin_ois', $pi['dt_assin_ois']) }}" />

                                    @error('dt_assin_ois')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Abertura:</label>
                                <div class="form-group">
                                    <input type="date" name="dt_abertura" id="dt_abertura"
                                        class="form-control  @error('dt_abertura') is-invalid @enderror"
                                        value="{{ old('dt_abertura', $pi['dt_abertura']) }}" />

                                    @error('dt_abertura')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>


                            </div>

                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Prazo</label>

                                <div class="form-group">
                                    <input type="text" name="prazo_item" id="prazo_item"
                                        class="form-control  @error('prazo_item') is-invalid @enderror" placeholder=""
                                        value="{{ old('prazo_item', $pi['prazo']) }}">

                                    @error('prazo_item')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md- col-form-label">Descrição:</label>
                                <div class="form-group">
                                    <textarea class="form-control" id="observacoes" name="observacoes"
                                        rows="3">{{ old('observacoes', $pi['descricao_item']) }}</textarea>
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
        {{-- Itens --}}
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#codigo_predio").change(function() {
                //function carrega predio.
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    },
                    type: 'POST',
                    url: "/carrega/predio",
                    data: 'codigoPredio=' + $(this).val(),
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
            $('#codigo_predio').mask('00.00.000');
            $('#codigo_pi').mask('0000 / 00000');
            $('#valor_total').mask('#.##0,00', {
                reverse: true
            });

            $('#valor_item').mask('#.##0,00', {
                reverse: true
            });
            //definir mascara pra prazo_total
        });

    </script>
@endpush
