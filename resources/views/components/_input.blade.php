<div class="col-md-{{ $size ?? '12' }}">
    <label class="col-md- col-form-label" for="{{ $id }}">{{ $label ?? '' }}</label>
    <div class="form-group">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value ?? '' }}" class="form-control {{ $class ?? '' }}" {{ $attributes ?? '' }}>
    </div>
</div>