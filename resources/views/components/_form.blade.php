<form action="{{ $action }}" method="{{ $method ?? 'GET' }}" {{ $attributes ?? '' }}>
    @if($method == 'POST')
        @csrf
    @endif
    {{ $slot }}
</form>