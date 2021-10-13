@extends('layouts.app', [
'class' => '',
'elementActive' => 'upload-zipMultiplos'
])


@section('content')
    @component('components._breadcrumb')
        @slot('list')
            <li class="breadcrumb-item"><a href="#">Zip Vistorias</a></li>
            <li class="breadcrumb-item active"
                aria-current="page">Descompactar Vistorias
            </li>
        @endslot
    @endcomponent

    @component('components.messages._messages')@endcomponent

    @component('components._content')
        @slot('slot')
            @component('components._card', [
               'title' => 'Cadastro anexo de vistoria Multiplas',
           ])

                @slot('body')
                    @component('components._form',[
                       'method' => 'POST',
                       'action' => route('zipArchiveMultiplos.descompact'),
                       'attributes' => 'enctype=multipart/form-data accept=application/pdf'
                   ])
                        @slot('slot')
                            <div class="row">
                                @component('components._input',[
                                    'size' => '12',
                                    'type' => 'file',
                                    'label' => 'Descompactar arquivo de vistoria: ',
                                    'name' => 'zipArchive',
                                    'id' => 'zipArchive',
                                    'attributes' => "accept=zip,application/octet-stream,application/zip,application/x-zip,application/x-zip-compressed",
                                    'value' => old('zipArchive')
                                ])
                                @endcomponent
                            </div>
                            @component('components.buttons._submit', ['attributes' => "style=margin-top:40px"])@endcomponent
                        @endslot
                    @endcomponent
                @endslot
            @endcomponent
        @endslot
    @endcomponent
@endsection
