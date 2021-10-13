<div class="col-md-{{ $size ?? '12' }}">
    <label class="col-md- col-form-label" for="{{ $id }}">{{ $label ?? '' }}</label>
    <div class="form-group">
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $value ?? '' }}" class="form-control {{ $class ?? '' }}" {{ $attributes ?? '' }}>
        
    </div>
    @if($errors->has($name))
        @if($type == 'file')
            <div class="invalid-feedback" style="display: block; position:relative; top: 40px;">
                {{ $errors->first($name) }}
            </div>
        @else
            <div class="invalid-feedback" style="display: block;">
                {{ $errors->first($name) }}
            </div>
        @endif
    @endif
</div>