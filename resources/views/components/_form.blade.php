<form action="{{ $action }}" id="{{ $id ?? '' }}" method="{{ $method ?? 'GET' }}" {{ $attributes ?? '' }}>
    @if($method == 'POST')
        @csrf
    @endif
    {{ $slot }}
</form>