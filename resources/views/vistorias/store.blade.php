@extends('layouts.app', [
'class' => '',
'elementActive' => 'vistorias'
])

@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Paramêtros</a></li>
            <li class="breadcrumb-item"><a href="{{ Route("predios.list") }}">Prédios</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ empty($predios['codigo']) ? 'Cadastro de Prédios' : 'Editar Cadastro de Prédio' }}</li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card')
                @slot('body')
                    @component('components._form',[
                        'method' => 'POST',
                        'action' => '#',
                        'attributes' => 'enctype=multipart/form-data'
                    ])
                        @slot('slot')
                            <div class="row">
                                @component('components._input', [
                                    'size' => '4',
                                    'type' => 'text',
                                    'label' => 'Código PI:',
                                    'name' => 'codigo',
                                    'id' => 'codigo'
                                ])
                                @endcomponent

                                @component('components._input', [
                                    'size' => '4',
                                    'type' => 'text',
                                    'label' => 'Prédio:',
                                    'name' => 'predio',
                                    'id' => 'predio',
                                    'class' => 'input-escondido',
                                    'attributes' => 'readonly'
                                ])
                                @endcomponent
                    
                                @component('components._input', [
                                    'size' => '4',
                                    'type' => 'text',
                                    'label' => 'Nome:',
                                    'name' => 'nome',
                                    'id' => 'nome',
                                    'class' => 'input-escondido',
                                    'attributes' => 'readonly'
                                ])
                                @endcomponent
                            </div>

                            <div class="row">
                                @component('components._select',[
                                    'size' => '4',
                                    'label' => 'Tipo Vistoria: ',
                                    'name' => 'tipo_vistoria',
                                    'id' => 'tipo_vistoria'
                                ])
                                    @slot('options')
                                        @foreach($vistoriaTipos as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                        @endforeach
                                    @endslot
                                @endcomponent

                                @component('components._input',[
                                    'size' => '4',
                                    'type' => 'date',
                                    'label' => 'Data de Abertura: ',
                                    'name' => 'data_abertura',
                                    'id' => 'data_abertura'
                                ])
                                @endcomponent

                                @component('components._input',[
                                    'size' => '4',
                                    'type' => 'file',
                                    'label' => 'Arquivo: ',
                                    'name' => 'arquivo_folha',
                                    'id' => 'arquivo_folha'
                                ])
                                @endcomponent
                            </div>
                            @component('components.buttons._submit')@endcomponent
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            //MASK
            $('#codigo').mask('00.00.000');
            $('#telefone').mask('(00) 00000-0000');
        });

    </script>
@endpush
