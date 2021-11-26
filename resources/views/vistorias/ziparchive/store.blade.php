@extends('layouts.app', [
'class' => '',
'elementActive' => 'upload-zip'
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
        @foreach($errors->get('input_array.*') as $errors)
                @foreach($errors as $error)
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                            <i class="nc-icon nc-simple-remove"></i>
                        </button>
                        <span><b> Atenção !:</b> {{ $error }}</span>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif

    @component('components._content')
        @slot('slot')
            @component('components._card', [
               'title' => 'Cadastro anexo de vistoria',
            ])

                @slot('body')
                    @component('components._form',[
                       'method' => 'POST',
                       'action' => route('zipArchive.descompact'),
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
