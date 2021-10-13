<div class="row">
    <div class="col-md-12 {{ $class ?? '' }} text-center">
        <button type="{{ $type ?? 'submit' }}" class="btn btn-info btn-round" {{ $attributes ?? '' }}>{{ $title ?? 'Salvar' }}</button>
    </div>
</div>