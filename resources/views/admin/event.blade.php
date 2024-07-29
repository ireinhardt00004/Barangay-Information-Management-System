@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240, 236, 236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Event Calendar</span></h1>
                <button id="create-event-btn" class="btn btn-primary mt-4"><i class="fas fa-calendar"></i>  Create Event</button>
            </div>
            <hr style="border:1px solid black;">
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Brgy. Calendar</h5>
                            <div id="brgy-calendar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Events</h5>
                            <ul class="list-unstyled" style="list-style-type: disc; list-style-position: inside; color: #333;">
                                @foreach($events as $event)
                                    <li class="mb-2"><h6><button class="btn btn-danger btn-sm" onclick="deleteEvent('{{ $event->id }}')"><i class="fas fa-trash"></i></button>
                                        <strong>{{ $event->title }}</strong> - {{ $event->type }}</h6><br>
                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('F d, Y h:i A') }} until {{ \Carbon\Carbon::parse($event->end_datetime)->format('F d, Y h:i A') }}
                                         </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .calendar {
        padding: .75rem;
        width: 100%;
        height: 100%;
        gap: 1rem;
        border-radius: .5rem;
        background-color: white;
        padding: 1rem .75rem;
        display: flex;
        margin-bottom: .75rem;
    }

    #calendar {
        padding: 1rem;
        width: 100%;
        height: 100%;
    }

    .Totalevents {
        padding: 1rem;
        width: 60%;
        height: 95%;
        background-color: #dddcdc;
        border-radius: .5rem;
        overflow-y: auto;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('brgy-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        height: 450,
        initialView: 'dayGridMonth',
        events: @json($calendarEvents),
        eventClick: function(info) {
            const event = info.event;
            Swal.fire({
                title: event.title,
                html: `
                    <p><strong>Description:</strong> ${event.extendedProps.description}</p>
                    <p><strong>Type:</strong> ${event.extendedProps.type}</p>
                    <p><strong>Start:</strong> ${event.start.toLocaleString()}</p>
                    <p><strong>End:</strong> ${event.end ? event.end.toLocaleString() : 'N/A'}</p>
                `,
                showCloseButton: true,
                showConfirmButton: false,
            });
        }
    });
    calendar.render();
});

document.getElementById('create-event-btn').addEventListener('click', function() {
    Swal.fire({
        title: 'Create Event',
        html: `
            <input id="title" class="swal2-input" placeholder="Title">
            <textarea id="description" class="swal2-textarea" placeholder="Description"></textarea>
            <select id="type" class="swal2-select">
                <option value="Regular Holiday">Regular Holiday</option>
                <option value="Special Holiday">Special Holiday</option>
                <option value="Others">Others</option>
            </select>
            <input id="start_datetime" class="swal2-input" type="datetime-local">
            <input id="end_datetime" class="swal2-input" type="datetime-local">
            <div style="text-align: left; margin-top: 10px;">
                <input type="checkbox" id="notify_residents">
                <label for="notify_residents">Notify residents via email too?</label>
            </div>
        `,
        confirmButtonText: 'Create',
        showCancelButton: true,
        preConfirm: () => {
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const type = document.getElementById('type').value;
            const start_datetime = document.getElementById('start_datetime').value;
            const end_datetime = document.getElementById('end_datetime').value;
            const notify_residents = document.getElementById('notify_residents').checked;

            return { title, description, type, start_datetime, end_datetime, notify_residents };
        }
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we create the event and send notifications.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route('events.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(result.value)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'There was an error processing your request.', 'error');
            });
        }
    });
});
</script>
<script>
    function deleteEvent(eventId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('/events/delete') }}/${eventId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Deleted!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                }).catch(error => {
                    Swal.fire('Error!', 'Failed to delete event. Please try again later.', 'error');
                });
            }
        });
    }
</script>
<script>
    const navLinks = document.querySelectorAll('.nav-link');
    const uri = window.location.href;
    const uriParts = uri.split('=');
    const uriValue = uriParts[uriParts.length - 1];
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        const hrefParts = href.split('=');
        const linkValue = hrefParts[hrefParts.length - 1];
    
        if (linkValue === uriValue) {
            link.classList.add('active-nav');
        }
    });
</script>
<!-- Stylesheets and Scripts -->
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title', 'Event Calendar')
