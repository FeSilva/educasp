@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@push('styles')
    <link rel="stylesheet" href="{{ asset('calendar/main.min.css') }}">
@endpush

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('calendar/main.min.js') }}"></script>

    <script>
        let events = "{{ $events }}";
        events = JSON.parse(events.replace(/&quot;/g,'"'));

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'pt-br',
                initialView: 'dayGridMonth',
                events: events
            });
            calendar.render();
        });
    </script>
@endpush