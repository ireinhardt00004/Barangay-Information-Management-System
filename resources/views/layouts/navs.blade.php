@php
    $gens = DB::table('general_confs')->get(); // Fetch gens data
    $navs = DB::table('navs')->get(); // Fetch navigation data
@endphp

@foreach ($gens as $gen)
<style>
    .navbar {
        background-color: #202020!important; /* Ensure this color is not being overridden */
    }
    .navbar-dark .navbar-nav .nav-link {
        color: #ffffff; /* Make sure text color contrasts with background */
    }
</style>

<nav id="navbar" class="navbar fixed-top navbar-dark navbar-expand-lg justify-content-center align-items-center">
    <div class="container">
        <a class="navbar-brand" id="cstm-nav-brand" style="font-size:14px" href="{{ url('/') }}">
            <img height="60" src="{{ asset('assets/head_logo/' . $gen->logo) }}" alt="Logo">
            {{ $gen->title }}
        </a>
        <button class="navbar-toggler nav-m-btn navbar-light shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navdrop" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navdrop">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link" id="home" href="{{ url('/') }}">HOME</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" id="about" href="{{ url('about') }}">ABOUT</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" id="announcements" href="{{ url('announcements') }}">ANNOUNCEMENT</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" id="service" href="{{-- url('service') --}}{{ url('service-types') }}">SERVICE</a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link" id="programs" href="{{ url('programs') }}">PROGRAMS</a>
                </li>

                @foreach($navs as $data)
                    @php
                        $nav = $data->nav_name;
                        $page_id = intval($data->page_id);
                        $page = DB::table('pages')->find($page_id);
                        $page_name = base64_decode($page->page_name);
                    @endphp
                    <li class="nav-item mx-1">
                        <a class="nav-link" href="{{ url($page_name) }}">{{ $nav }}</a>
                    </li>
                @endforeach

            </ul>
            <div class="text-white">
                @php
                    $em_contact = json_decode($gen->em_contacts);
                    $em_city_hall = base64_decode($em_contact->city_hall ?? '');
                    $em_police = base64_decode($em_contact->police ?? '');
                @endphp
                <p class="m-0"><span>Barangay Hall #:</span> {{ $em_city_hall }}</p>
                <p class="m-0"><span>Police Station #:</span> {{ $em_police }}</p>
            </div>
        </div>
    </div>
    <script>
        const navLinks = document.querySelectorAll('.nav-link');
        const uri = window.location.href;
        const uriParts = uri.split('/');
        const uriValue = uriParts[uriParts.length - 1];
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            const hrefParts = href.split('/');
            const linkValue = hrefParts[hrefParts.length - 1];
            if (linkValue === uriValue) {
                link.classList.add('active-nav2');
            }
        });
    </script>
    <style>
        @media (max-width: 768px) {
            .carousel-content h1 {
                font-size: 1.5rem!important;
            }
            .navbar {
                background-color: #202020!important;
            }
            .navbar-nav {
                text-align: center; 
                display: flex!important;
            }
            .nav-item {
                padding: 5px!important;
            }
            .nav-item a {
                padding: 5px!important;
            }
        }
    </style>
</nav>
@endforeach
