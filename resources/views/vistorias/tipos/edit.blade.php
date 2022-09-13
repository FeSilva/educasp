@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])


@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="../tipos">Tipos de Vistoria</a></li>
            <li class="breadcrumb-item active"
                aria-current="page">Editar Tipo de Vistoria</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
                'title' => 'Editar o Tipo de Vistoria',
            ])

                @slot('body')
                    @component('components._form', [
                        'action' => 'vistoria.tipos.update',
                        'method' => 'post'
                    ])
                    @csrf
                        @slot('slot')
                            <div class="row">
                                @component('components._input', [
                                    'size' => '4',
                                    'type' => 'text',
                                    'label' => 'Tipo da Vistoria:',
                                    'name' => 'name',
                                    'id' => 'name',
                                    'class' => 'form-control',
                                    'value' => old('tipo_vistoria') ?? $tipo->name
                                ])
                                @endcomponent
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Sigla:',
                                    'name' => 'sigla',
                                    'id' => 'sigla',
                                    'class' => 'form-control',
                                    'value' => old('sigla') ?? $tipo->sigla
                                ])
                                @endcomponent
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Valor a Pagar:',
                                    'name' => 'price',
                                    'id' => 'price',
                                    'class' => 'form-control',
                                    'value' => old('price') ?? $tipo->price
                                ])
                                @endcomponent
                                @component('components._input', [
                                    'size' => '2',
                                    'type' => 'text',
                                    'label' => 'Valor a Receber:',
                                    'name' => 'amount_to_receive',
                                    'id' => 'amount_to_receive',
                                    'class' => 'form-control',
                            
                                    'value' => old('amount_to_receive') ?? $tipo->amount_to_receive
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
                                        <option value='active' selected>Ativo</option>
                                        <option value='inactive'> Desativado </option>
                                    @endslot
                                @endcomponent
                    
                            </div>
                            <div class="row">
                       
                                @component('components._input', [
                                    'size' => '12',
                                    'type' => 'text',
                                    'label' => 'Descrição:',
                                    'name' => 'description',
                                    'id' => 'description',
                                    'class' => 'form-control',
                                    'value' => old('description')
                                ])
                                @endcomponent
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @component('components.buttons._submit',['title'=> 'Atualizar'])@endcomponent
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
