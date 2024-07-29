@extends('layouts.appres')
@section('content')

{{-- <section class="section-1">
<form action="">
<input type="text" placeholder="Share some information kabarangay">
<div class="formDiv">
    <div class="fileSelect">

        <svg width="800px" height="800px" viewBox="0 -0.5 18 18"
            xmlns="http://www.w3.org/2000/svg" fill="#000000">

            <g id="SVGRepo_bgCarrier" stroke-width="0" />

            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

            <g id="SVGRepo_iconCarrier">
                <path fill="#000000" fill-rule="evenodd"
                    d="M474.188327,259.775909 L480.842912,259.775909 L477.549999,256.482996 L474.375904,259.65709 C474.321124,259.71187 474.256777,259.751373 474.188327,259.775909 Z M474,258.618781 L474,247.775909 L486,247.775909 L486,254.968826 L483.657827,252.626653 C483.470927,252.439753 483.148791,252.4342 482.953529,252.629462 C482.940375,252.642616 482.928101,252.656403 482.916711,252.670736 C482.913161,252.674075 482.909651,252.677479 482.906183,252.680947 L479.034173,256.552957 L477.918719,255.437503 C477.808988,255.327771 477.655516,255.279359 477.507786,255.29536 C477.387162,255.302309 477.267535,255.351246 477.17513,255.44365 L474,258.618781 Z M482.257125,259.775909 L486,259.775909 L486,256.377007 L485.996984,256.380023 L483.309152,253.692192 L479.74128,257.260064 L482.257125,259.775909 Z M487,259.406871 L487.960593,259.541874 C488.51207,259.619379 489.020377,259.235606 489.097766,258.684953 L490.765938,246.815293 C490.843443,246.263816 490.459671,245.75551 489.909017,245.678121 L478.039358,244.009949 C477.487881,243.932444 476.979574,244.316216 476.902185,244.86687 L476.633887,246.775909 L474.006845,246.775909 C473.449949,246.775909 473,247.226689 473,247.782754 L473,259.769063 C473,260.32596 473.45078,260.775909 474.006845,260.775909 L485.993155,260.775909 C486.550051,260.775909 487,260.325128 487,259.769063 L487,259.406871 Z M487,258.397037 L488.10657,258.552556 L489.776647,246.669339 L477.89343,244.999262 L477.643739,246.775909 L485.993155,246.775909 C486.54922,246.775909 487,247.225857 487,247.782754 L487,258.397037 Z"
                    transform="translate(-473 -244)" />
            </g>

        </svg>
        <input type="file" name="" id="">
    </div>

    <button type="button">
        <svg version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" width="800px" height="800px"
            viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve"
            fill="#000000">

            <g id="SVGRepo_bgCarrier" stroke-width="0" />

            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

            <g id="SVGRepo_iconCarrier">
                <g>
                    <path fill="#231F20"
                        d="M34.929,7.629c-0.205-0.513-0.787-0.763-1.297-0.557c-0.513,0.203-0.764,0.784-0.562,1.297 c0.019,0.047,1.84,4.804-0.032,11.356c0,0-0.001,0.007-0.001,0.011c-1.215,1.103-2.459,2.271-3.744,3.557 c-1.357,1.357-2.591,2.671-3.745,3.948c0.998-9.183-0.498-16.128-0.571-16.458c-0.12-0.539-0.654-0.876-1.193-0.76 c-0.539,0.12-0.879,0.654-0.76,1.193c0.019,0.086,1.823,8.469,0.13,18.763c-2.26,2.695-4.05,5.14-5.464,7.261 c0.709-7.9-0.644-14.145-0.713-14.457c-0.12-0.539-0.65-0.886-1.193-0.759c-0.539,0.119-0.879,0.653-0.76,1.192 c0.02,0.087,1.885,8.75,0.048,18.297c-1.383,2.488-1.953,3.988-2.01,4.141c-0.19,0.518,0.074,1.092,0.592,1.283 C13.768,46.979,13.885,47,14,47c0.406,0,0.788-0.25,0.938-0.653c0.013-0.034,0.5-1.302,1.684-3.468 c10.438-2.726,19.995,0.051,20.092,0.079C36.809,42.986,36.905,43,37,43c0.431,0,0.828-0.28,0.958-0.714 c0.158-0.528-0.142-1.085-0.671-1.244C36.897,40.924,28.2,38.391,18,40.506c1.416-2.316,3.406-5.218,6.108-8.52 c0.052-0.006,0.104-0.008,0.154-0.021c10.595-2.889,21.367-0.029,21.475,0C45.825,31.988,45.913,32,46,32 c0.44,0,0.844-0.292,0.965-0.737c0.145-0.533-0.169-1.082-0.702-1.228c-0.425-0.116-9.801-2.598-20.012-0.576 c1.341-1.524,2.814-3.109,4.456-4.752c1.41-1.41,2.777-2.693,4.103-3.88c6.452-1.649,12.852,0.116,12.916,0.135 C47.817,20.987,47.909,21,48,21c0.436,0,0.836-0.287,0.961-0.727c0.151-0.53-0.155-1.083-0.687-1.235 c-0.239-0.067-4.9-1.367-10.473-0.779c8.531-7.021,14.472-9.294,14.545-9.321c0.518-0.191,0.782-0.767,0.591-1.284 c-0.191-0.519-0.766-0.779-1.283-0.592c-0.326,0.12-6.805,2.58-16.068,10.431C36.527,11.735,35.004,7.816,34.929,7.629z" />
                    <path fill="#231F20"
                        d="M60.893,1.549c-0.136-0.269-0.386-0.462-0.679-0.525c-2.98-0.652-6.97-0.982-11.856-0.982 c-4.922,0-10.564,0.353-15.481,0.967C17.641,2.912,7,13.601,7,27v18.678L3.103,60.225c-0.428,1.598,0.523,3.244,2.122,3.674 c1.598,0.426,3.245-0.525,3.673-2.121L11.25,53H31c14.337,0,26-11.663,26-26c0-6.663,0-15.788,3.914-24.594 C61.036,2.132,61.028,1.816,60.893,1.549z M6.966,61.26c-0.143,0.532-0.691,0.849-1.224,0.707 c-0.534-0.145-0.851-0.691-0.708-1.225l2.552-9.686c0.405,0.672,0.998,1.212,1.712,1.55L6.966,61.26z M55,27 c0,13.233-10.767,24-24,24H11c-1.104,0-2-0.896-2-2v-1V27C9,14.641,18.92,4.769,33.124,2.992 c4.839-0.604,10.391-0.951,15.233-0.951c4.048,0,7.553,0.242,10.238,0.705C55,11.565,55,20.443,55,27z" />
                </g>
            </g>

        </svg>
        Activity
    </button>
</div>
</form>

</section> --}}

<div class="calendar">
<div id="calendar"></div>
<div class="Totalevents">
<h3>Total events</h3>
@php
use Carbon\Carbon;
@endphp

<ul style="list-style-type: disc; list-style-position: inside; color: black;">
    @foreach($events as $data)
    <li>
        {{ $data->title }} - {{ $data->type}}
        <br>
        {{ Carbon::parse($data->start_datetime)->format('F d, Y h:i A') }} until {{ Carbon::parse($data->end_datetime)->format('F d, Y h:i A') }}
    </li>
    @endforeach
</ul>

</div>
</div>
<div class="chart">
<h1>Request Status Chart</h1>
<canvas id="statusChart" width="200" height="50"></canvas>
</div>
@foreach($announcements as $announcement)
<div class="tulongPost">
<div class="tulongtext">
<div class="tulongpara example">
    <h1>{{ $announcement->decoded_title }}</h1>
    <h6>{{ \Carbon\Carbon::parse($announcement->created_at)->format('F d, Y h:i A') }}</h6>
    <p>{{ $announcement->decoded_content }}</p>
</div> 
<div class="tulongButton">
    <div class="tulongButton">
        @livewire('heart-react', ['announcementId' => $announcement->id])
        @livewire('comment-section', ['announcementId' => $announcement->id])
        {{-- <button>
            <svg width="800px" height="800px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path
                        d="M13.47 4.13998C12.74 4.35998 12.28 5.96 12.09 7.91C6.77997 7.91 2 13.4802 2 20.0802C4.19 14.0802 8.99995 12.45 12.14 12.45C12.34 14.21 12.79 15.6202 13.47 15.8202C15.57 16.4302 22 12.4401 22 9.98006C22 7.52006 15.57 3.52998 13.47 4.13998Z"
                        stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </g>
            </svg>
        </button> --}}
    </div>
</div>
</div>
<div class="tulongimgs" title="Click to enlarge" style="cursor: pointer;">
<img src="{{ asset('announcement_img/' . $announcement->decoded_cover) }}" alt="" data-image="{{ asset('announcement_img/' . $announcement->decoded_cover) }}" class="enlarge-image">
</div>
</div>
@endforeach
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Existing image enlarge script

// Data for the chart
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
</script>
<script>
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
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            height: 450,
            initialView: 'dayGridMonth',
            events: @json($calendarEvents),
        });
        calendar.render();
    });
</script>
<script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                'Active user',
                'Inactive user',
                'Total users'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [3, 5, 1],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection