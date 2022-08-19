@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@push('styles')
    <link rel="stylesheet" href="{{ asset('calendar/main.min.css') }}">
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Calendário</li>
        </ol>
    </nav>
    <div class="content">
        <div class="row">
            <div class="col-12">
                @component('components._card', [
                    'title' => 'Calendário de Vistorias'
                ])
                    @slot('body')
                        <div id="calendar"></div>
                    @endslot
                @endcomponent
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
                events: events,
                initialDate: events[0]['start'].replace(/(\d{2}-\d{2}-\d{2})/, '$1')
            });
            calendar.render();
        });
    </script>
@endpush