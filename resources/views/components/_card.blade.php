<div class="card" style="width: 100%" id="{{ $cardId ?? '' }}">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 class="title">{{ $title ?? '' }}</h5>
            </div>
            <div class="col-4 text-right">
                @isset ($route)
                    <a href="{{ $route }}" class="btn btn-sm btn-primary" {{ $attributes ?? '' }} id="{{ $id ?? '' }}">{{ $titleBtn ?? 'Cadastrar' }}</a>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        {{ $body }}
    </div>

    <div class="card-footer">
        {{ $footer ?? ''}}
    </div>
</div>