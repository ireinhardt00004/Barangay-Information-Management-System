@extends('layouts.appres')
@section('content')

<div class="container">
    <div class="calendar-container">
        <div id="calendar"></div>
        <div class="total-events">
            <h3>Total Events</h3>
            @php
            use Carbon\Carbon;
            @endphp
            <ul class="events-list">
                @foreach($events as $data)
                <li>
                    <strong>{{ $data->title }}</strong> - {{ $data->type }}
                    <br>
                    {{ Carbon::parse($data->start_datetime)->format('F d, Y h:i A') }} until {{ Carbon::parse($data->end_datetime)->format('F d, Y h:i A') }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <section class="announcements-section">
        @foreach($announcements as $announcement)
        <div class="announcement-post">
            <div class="announcement-text">
                <h1>{{ $announcement->decoded_title }}</h1>
                <h6>{{ Carbon::parse($announcement->created_at)->format('F d, Y h:i A') }}</h6>
                <p>{{ $announcement->decoded_content }}</p>
            </div> 
            <div class="announcement-buttons">
                @livewire('heart-react', ['announcementId' => $announcement->id])
                @livewire('comment-section', ['announcementId' => $announcement->id])
            </div>
            <div class="announcement-image" title="Click to enlarge" style="cursor: pointer;">
                <img src="{{ asset('announcement_img/' . $announcement->decoded_cover) }}" alt="" data-image="{{ asset('announcement_img/' . $announcement->decoded_cover) }}" class="enlarge-image">
            </div>
        </div>
        @endforeach
    </section>

    <section class="chart-section">
        <h1>Request Status Chart</h1>
        <canvas id="statusChart" width="400" height="200"></canvas>
    </section>
</div>

<style>
    .container {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px;
    }

    .calendar-container {
        display: flex;
        flex-direction: column;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .total-events {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
    }

    .events-list {
        list-style-type: disc;
        list-style-position: inside;
        color: #333;
        padding: 0;
        margin: 0;
    }

    .events-list li {
        margin-bottom: 10px;
    }

    .announcements-section {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        margin-bottom: .5rem ;
    }

    .announcement-post {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        flex: 1 1 calc(33% - 20px);
        display: flex;
        flex-direction: column;
    }

    .announcement-text h1 {
        margin: 0 0 10px;
        font-size: 1.5em;
    }

    .announcement-text h6 {
        margin: 0 0 10px;
        color: #888;
    }

    .announcement-buttons {
        margin-top: auto;
        display: flex;
        gap: 10px;
    }

    .announcement-image img {
        width: 70%;
        height: 70%;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .announcement-image{
        display: flex;
        justify-content: center;
        align-content: center;
    }

    .announcement-image img:hover {
        transform: scale(.5);
    }

    .chart-section {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .chart-section h1 {
        margin-bottom: 20px;
    }
    
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusCounts = @json($statusCounts);

    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Approved', 'Declined'],
            datasets: [{
                data: [statusCounts['pending'], statusCounts['approved'], statusCounts['declined']],
                backgroundColor: ['#FFCE56', '#36A2EB', '#FF6384'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.enlarge-image').forEach(image => {
        image.addEventListener('click', function() {
            const imageUrl = this.getAttribute('data-image');

            Swal.fire({
                imageUrl: imageUrl,
                imageWidth: 800,  
                imageHeight: 600, 
                imageAlt: 'Image',
                showCloseButton: true,
                showConfirmButton: false,
                width: '80%',
                height: '80%',
                customClass: {
                    container: 'sweetalert2-container'
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        height: 450,
        initialView: 'dayGridMonth',
        events: @json($calendarEvents),
        eventClick: function(info) {
            const event = info.event;
            Swal.fire({
                title: event.title,
                html: `
                    <p><strong>Type:</strong> ${event.extendedProps.type}</p>
                    <p><strong>Description:</strong> ${event.extendedProps.description}</p>
                    <p><strong>Start:</strong> ${new Date(event.start).toLocaleString()}</p>
                    <p><strong>End:</strong> ${new Date(event.end).toLocaleString()}</p>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '50%',
                customClass: {
                    container: 'sweetalert2-container'
                }
            });
        }
    });
    calendar.render();
});
</script>

@endsection
