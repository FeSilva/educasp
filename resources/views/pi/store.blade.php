@extends('layouts.app', [
'class' => '',
'elementActive' => 'pi'
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
            <form class="col-md-12" action="{{ route('pi.create') }}" method="POST" style="overflow: auto">
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
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">RV</label>

                                <div class="form-group">
                                    <input type="text" name="rv" id="rv"
                                           class="form-control @error('rv') is-invalid @enderror" placeholder=""
                                           value="{{ old('rv', $pi['rv']) }}">

                                    @error('codigo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Nº Contrato </label>

                                <div class="form-group">
                                    <input type="text" name="numero_contrato" id="numero_contrato"
                                        class="form-control @error('numero_contrato') is-invalid @enderror" placeholder=""
                                        value="{{ old('numero_contrato', $pi['numero_contrato']) }}">

                                    @error('numero_contrato')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Nº OS </label>

                                <div class="form-group">
                                    <input type="text" name="numero_os" id="numero_os"
                                        class="form-control @error('numero_os') is-invalid @enderror" placeholder=""
                                        value="{{ old('numero_os', $pi['numero_os']) }}">

                                    @error('numero_os')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Vistorias Mês </label>

                                <div class="form-group">
                                    <input type="number" name="qtde_vistorias_mes" id="qtde_vistorias_mes"
                                        class="form-control @error('qtde_vistorias_mes') is-invalid @enderror" placeholder=""
                                        value="{{ (old('qtde_vistorias_mes', $pi['qtde_vistorias_mes']) ?? '4') }}">

                                    @error('qtde_vistorias_mes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-md- col-form-label">Nº Gestão Social</label>

                                <div class="form-group">
                                    <input type="number" name="numero_gestao_social" id="numero_gestao_social"
                                        class="form-control @error('numero_gestao_social') is-invalid @enderror" placeholder=""
                                        value="{{ (old('numero_gestao_social', $pi['numero_gestao_social']) ?? '4') }}">

                                    @error('numero_gestao_social')
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

                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Nome Contratada:</label>

                                <div class="form-group">
                                    <input type="text" name="nome_contratada" id="nome_contratada"
                                        class="form-control @error('nome_contratada') is-invalid @enderror"
                                        value="{{ old('nome_contratada', $pi['nome_contratada']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Assinatura: <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="date" name="assinatura" id="assinatura"
                                        class="form-control @error('assinatura') is-invalid @enderror"
                                        value="{{ old('assinatura', $pi['dt_assinatura']) }}">

                                    @error('assinatura')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Valor Total <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="valor_total" id="valor_total"
                                        class="form-control @error('valor_total') is-invalid @enderror"
                                        value="{{ old('valor_total', $pi['valor_total']) }}">
                                    @error('valor_total')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Prazo Total <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    <input type="text" name="prazo_total" id="Prazo_total"
                                        class="form-control @error('prazo_total') is-invalid @enderror"
                                        value="{{ old('prazo_total', $pi['prazo_total']) }}">

                                    @error('prazo_total')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Contratação </label>

                                <div class="form-group">
                                    {{ Form::select('contratacao', $contratacao, old('contratacao', $pi['contratacao']), ['id' => 'contratacao', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}

                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Fiscal <span style="color:red;">*</span></label>

                                <div class="form-group">

                                    {{ Form::select('fiscais', $fiscais, old('fiscais', $pi['id_user']), ['id' => 'fiscais', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}

                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Empresa Contratada <span
                                        style="color:red;">*</span></label>

                                <div class="form-group">
                                    {{ Form::select('empreiteiras', $empreiteiras, old('empreiteiras', $pi['id_empreiteira']), ['id' => 'empreiteiras', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Programa </label>
                                <div class="form-group">
                                    <input list="sel-opts" id="new-opt" class="form-control" name="programa"
                                        value="{{ old('programa', $pi['programa']) }}" />
                                    <small><span id="add-btn" class="btn btn-success" disabled>Adicionar +</span></small>
                                    <datalist id="sel-opts">
                                        @foreach ($programas as $key => $programa)

                                            <option value="{{ $programa }}">{{ $programa }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <small><span id="msg_return"></span></small>
                            </div>

                            <div class="col-md-3">
                                <label class="col-md- col-form-label">Objeto do PI <span style="color:red;">*</span></label>

                                <div class="form-group">
                                    {{ Form::select('objeto_pi', $oPi, old('objeto_pi', $pi['objeto_pi']), ['id' => 'objeto_pi', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}


                                    @error('objeto_pi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="col-md- col-form-label">Descrição </label>
                                <div class="form-group">
                                    <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao"
                                        name="descricao" rows="3">{{ old('descricao', $pi['descricao']) }}</textarea>

                                    @error('descricao')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
        @if ($pi['id'] > 0)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Itens </h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="{{ route('items.store', ['id_pi' => isset($pi['id']) ? $pi['id'] : '']) }}"
                                        class="btn btn-sm btn-primary" data-toggle="modal"
                                       data-target="#modalCreate"
                                        class="btn btn-sm btn-primary">Cadastrar</a>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table yajra-dt" id="table">
                                    <thead class=" text-primary">
                                        <th>
                                            Nº Item
                                        </th>
                                        <th>
                                            Tipo Item
                                        </th>
                                        <th>
                                            prazo
                                        </th>
                                        <th>
                                            Valor
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </thead>
                                    <tbody>
                                        @if (isset($pi['items'][0]))
                                            @foreach ($pi['items'] as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item['num_item'] }}
                                                    </td>
                                                    <td>
                                                        {{ $item['tipo_item'] }}
                                                    </td>
                                                    <td>
                                                        {{ $item['prazo'] }}
                                                    </td>
                                                    <td>
                                                        {{ $item['valor'] }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('items.store', ['id' => $item['id']]) }}"
                                                            class="btn btn-primary" data-toggle="modal"
                                                            data-target="#modalEdit{{ $item['id'] }}">Editar</a>
                                                        <button type="button" class="btn btn-warning"
                                                            onclick="deleteItem('{{ $item['id'] }}','{{ $item['id_pi'] }}')">Deletar</button>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" tabindex="-1" role="dialog"
                                                    aria-labelledby="myLargeModalLabel" id="modalEdit{{ $item['id'] }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Cadastrar
                                                                    Item do PI</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form class="col-md-12"
                                                                    action="{{ route('items.create') }}" method="POST">
                                                                    <input name="_token" type="hidden"
                                                                        value="{{ csrf_token() }}" />
                                                                    <input type="hidden" name="id_pi" class="form-control"
                                                                        placeholder=""
                                                                        value="{{ !empty($pi['id']) ? $pi['id'] : '' }}" />
                                                                    <input type="hidden" name="id" id="id"
                                                                        class="form-control" placeholder=""
                                                                        value="{{ !empty($item['id']) ? $item['id'] : '' }}" />
                                                                    <div class="row">

                                                                        <div class="col-md-4">
                                                                            <label class="col-md- col-form-label">Item
                                                                                PI</label>

                                                                            <div class="form-group">
                                                                                <input type="text" name="item_pi"
                                                                                    class="form-control @error('item_pi') is-invalid @enderror"
                                                                                    placeholder=""
                                                                                    value="{{ old('item_pi', $item['num_item']) }}" />

                                                                                @error('item_pi')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label class="col-md- col-form-label">Tipo
                                                                                Item</label>

                                                                            <div class="form-group">
                                                                                {{ Form::select('tipo_item', $items, old('tipo_item', $item['tipo_item']), ['id' => 'tipo_item', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}


                                                                                @error('tipo_item')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label
                                                                                class="col-md- col-form-label">Valor</label>
                                                                            <div class="form-group">
                                                                                <input type="text" name="valor_item"
                                                                                    id="valor_item"
                                                                                    class="form-control  @error('valor_item') is-invalid @enderror"
                                                                                    placeholder=""
                                                                                    value="{{ old('valor_item', $item['valor']) }}" />
                                                                                @error('valor_item')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label
                                                                                class="col-md- col-form-label">OIS/OS</label>

                                                                            <div class="form-group">
                                                                                <input type="date" name="dt_assin_ois"
                                                                                    id="dt_assin_ois"
                                                                                    class="form-control  @error('dt_assin_ois') is-invalid @enderror"
                                                                                    value="{{ old('dt_assin_ois', $item['dt_assin_ois']) }}" />

                                                                                @error('dt_assin_ois')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label
                                                                                class="col-md- col-form-label">Abertura:</label>
                                                                            <div class="form-group">
                                                                                <input type="date" name="dt_abertura"
                                                                                    id="dt_abertura"
                                                                                    class="form-control  @error('dt_abertura') is-invalid @enderror"
                                                                                    value="{{ old('dt_abertura', $item['dt_abertura']) }}" />

                                                                                @error('dt_abertura')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>


                                                                        </div>
                                                                        <di class="col-md-4">
                                                                            <label
                                                                                class="col-md- col-form-label">Prazo</label>

                                                                            <div class="form-group">
                                                                                <input type="text" name="prazo_item"
                                                                                    id="prazo_item"
                                                                                    class="form-control  @error('prazo_item') is-invalid @enderror"
                                                                                    placeholder=""
                                                                                    value="{{ old('prazo_item', $item['prazo']) }}">

                                                                                @error('prazo_item')
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $message }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </di>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label
                                                                                class="col-md- col-form-label">Descrição:</label>
                                                                            <div class="form-group">
                                                                                <textarea class="form-control"
                                                                                    id="observacoes" name="observacoes"
                                                                                    rows="3">{{ old('observacoes', $item['descricao_item']) }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Salvar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5" align="center"> Nenhum registro encontrado. </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modalCreate"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Cadastrar Item do PI</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="col-md-12" action="{{ route('items.create') }}" method="POST">

                                <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                                <input type="hidden" name="id_pi" class="form-control" placeholder=""
                                    value="{{ !empty($pi['id']) ? $pi['id'] : '' }}" />

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-md- col-form-label">Item PI</label>

                                        <div class="form-group">
                                            <input type="text" name="item_pi"
                                                class="form-control @error('item_pi') is-invalid @enderror" placeholder=""
                                                value="{{ old('item_pi', '') }}" />

                                            @error('item_pi')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md- col-form-label">Tipo Item</label>

                                        <div class="form-group">
                                            {{ Form::select('tipo_item', $items, old('tipo_item', ''), ['id' => 'tipo_item', 'class' => 'form-control', 'placeholder' => 'Selecione']) }}


                                            @error('tipo_item')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md- col-form-label">Valor</label>
                                        <div class="form-group">
                                            <input type="text" name="valor_item" id="valor_item"
                                                class="form-control  @error('valor_item') is-invalid @enderror"
                                                placeholder="" value="{{ old('valor_item', '') }}" />
                                            @error('valor_item')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-md- col-form-label">OIS/OS</label>

                                        <div class="form-group">
                                            <input type="date" name="dt_assin_ois" id="dt_assin_ois"
                                                class="form-control  @error('dt_assin_ois') is-invalid @enderror"
                                                value="{{ old('dt_assin_ois', '') }}" />

                                            @error('dt_assin_ois')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md- col-form-label">Abertura:</label>
                                        <div class="form-group">
                                            <input type="date" name="dt_abertura" id="dt_abertura"
                                                class="form-control  @error('dt_abertura') is-invalid @enderror"
                                                value="{{ old('dt_abertura', '') }}" />

                                            @error('dt_abertura')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>


                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-md- col-form-label">Prazo</label>

                                        <div class="form-group">
                                            <input type="text" name="prazo_item" id="prazo_item"
                                                class="form-control  @error('prazo_item') is-invalid @enderror"
                                                placeholder="" value="{{ old('prazo_item', '') }}">

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
                                                rows="3">{{ old('observacoes', '') }}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Salvar</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endif
        {{-- Itens --}}
    </div>
@endsection

@if (!empty($errors->all()))
    @push('scripts')
        <script>
            $('#modalCreate').modal('show');
        </script>
    @endpush
@endif

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
                        alert('Por favor informe um código de Prédio valido..');
                    }
                });
            });

            //MASK
            $('#codigo').mask('0000/00000');
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
