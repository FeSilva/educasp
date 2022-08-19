@extends('layouts.app', [
'class' => '',
'elementActive' => 'Relatórios Gerênciais'
])

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Relatório Gerêncial</a></li>
            <li class="breadcrumb-item active" aria-current="page">Supervisor</li>
        </ol>
    </nav>
    <div class="content">
        <div class="row">
            <div class="col-12">
                @component('components._card', [
                    'title' => 'Relatório Gerêncial'
                ])
                    @slot('body')
                        @component('components._form', ['action' => "supervisor/gerarXml",'method' => 'POST',
                        ])
                            @slot('slot')
                                <div class="row">
                                    <div class="col-6">
                                        @component('components._select', [
                                            'label' => 'Fiscal: ',
                                            'name' => 'fiscal',
                                            'attributes'=> 'required',
                                            'id' => 'fiscal'
                                        ])
                                            @slot('options')
                                            <option value="" readonly>Selecione um Fiscal</option>
                                                @foreach($fiscais as $fiscal)
                                                    <option value="{{ $fiscal->id }}"> {{ $fiscal->name }}</option>
                                                @endforeach
                                            @endslot
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components._select', [
                                            'label' => 'Tipo de Relatório: ',
                                            'name' => 'tipo_relatorio',
                                            'attributes'=> 'required',
                                            'id' => 'tipo_relatorio'
                                        ])
                                            @slot('options')
                                                <option value='rt_custo'>Relatórios de Custo</option>
                                                <option value='rt_roteiro'>Relatório de Roteiro</option>
                                            @endslot
                                        @endcomponent
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        @component('components._input', [
                                            'label' => 'Competência: ',
                                            'type' => 'text',
                                            'name' => 'competencia',
                                            'attributes'=>'required' ,
                                            'id' => 'competencia'
                                        ])
                                        @endcomponent
                                    </div>
                                </div>
                                @component('components.buttons._submit', ['title' => 'Gerar'])@endcomponent
                            @endslot
                        @endcomponent
                    @endslot
                @endcomponent
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        $("#competencia").mask('99',{placeholder:"mm"});
    </script>
@endpush
    