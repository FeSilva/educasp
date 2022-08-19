@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])


@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="../tipos">Tipos de Vistoria</a></li>
            <li class="breadcrumb-item active"
                aria-current="page">Cadastrar Tipo de Vistoria</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Cadastro dos Tipos de Vistorias',
            ])

                @slot('body')
                    @component('components._form', [
                        'action' => '#',
                        'method' => 'GET'
                    ])
                        @slot('slot')
                            <div class="row">
                                @component('components._input', [
                                    'size' => '4',
                                    'type' => 'text',
                                    'label' => 'Tipo da Vistoria:',
                                    'name' => 'tipo_vistoria',
                                    'id' => 'tipo_vistoria',
                                    'class' => 'form-control',
                                
                                    'value' => old('tipo_vistoria')
                                ])
                                @endcomponent
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Sigla:',
                                    'name' => 'sigla',
                                    'id' => 'sigla',
                                    'class' => 'form-control',
                                    'value' => old('sigla')
                                ])
                                @endcomponent
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Valor Real:',
                                    'name' => 'amount',
                                    'id' => 'amount',
                                    'class' => 'form-control',
                                    'value' => old('amount')
                                ])
                                @endcomponent
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Valor a Receber:',
                                    'name' => 'amount_to_receive',
                                    'id' => 'amount_to_receive',
                                    'class' => 'form-control',
                            
                                    'value' => old('amount_to_receive')
                                ])
                                @endcomponent
                                @component('components._select', [
                                    'size' => '2',
                                    'label' => 'Status:',
                                    'name' => 'Status',
                                    'id' => 'status'
                                ])
                                    @slot('options')
                                        <option selected>Selecione</option>
                                        <option value='active'>Ativo</option>
                                        <option value='inactive'> Desativado </option>
                                    @endslot
                                @endcomponent
                    
                            </div>
                            <div class="row">
                       
                                @component('components._input', [
                                    'size' => '12',
                                    'type' => 'text',
                                    'label' => 'Descrição:',
                                    'name' => 'amount',
                                    'id' => 'amount',
                                    'class' => 'form-control',
                                    'value' => old('amount')
                                ])
                                @endcomponent
                        
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @component('components.buttons._submit',['title'=>'Criar Tipos de Vistoria'])@endcomponent
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
    <script type="text/javascript">

        $(document).ready(function () {

        });

        function dateFormat(date) {
            return date.split('-').reverse().join('/');
        }

    </script>
    @if(session()->has('tipo_vistoria')))
    <script>
        openFieldsIfHasErrors("{{ session()->get('tipo_vistoria') }}")
    </script>
    @endif
@endpush
