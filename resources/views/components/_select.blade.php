<div class="col-md-{{ $size ?? '12' }}">
    <label for="col-md- col-form-label" style="padding: 10px 0 6px 0;" for="{{ $id }}">{{ $label ?? '' }}</label>
    <div class="form-group">
        <select name="{{ $name }}" id="{{ $id }}" class="form-control {{ $class ?? '' }}" {{ $attributes ?? ''}}>
            {{ $options }}
        </select>
    </div>
    @if($errors->has($name))
        <div class="invalid-feedback" style="display: block;">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>