<table class="table {{ $class ?? '' }}" id="{{ $id_table ?? 'table' }}">
    <thead class="text-primary">
        {{ $thead }}
    </thead>
    <tbody id="{{ $body_id ?? '' }}" >
        {{ $tbody }}
    </tbody>
    @if (isset($tfoot))
        <tfoot>
            {{ $tfoot }}
        </tfoot>
    @endif
</table>