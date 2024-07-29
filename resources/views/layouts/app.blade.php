<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        use App\Models\GeneralConf;

        // Fetch the configuration data
        $generalConfig = GeneralConf::first();
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $generalConfig->meta_desc ?? 'Default description' }}">
    <meta property="og:title" content="{{ $generalConfig->title ?? 'Default Title' }}" />
    <meta property="og:description" content="{{ $generalConfig->meta_desc ?? 'Default description' }}" />
    <meta property="og:image" content="{{ asset('assets/head_logo/' . ($generalConfig->logo ?? 'default-logo.png')) }}" />
    <title>@yield('title', $generalConfig->head_title ?? 'Default Title')</title>

    <!-- CSS -->
    <link rel="shortcut icon" href="{{ asset('assets/head_logo/' . ($generalConfig->logo ?? 'default-logo.png')) }}" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/111c11b663.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://unpkg.com/fullcalendar@5.10.0/main.min.css" rel="stylesheet">
    <script src="https://unpkg.com/fullcalendar@5.10.0/main.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/js/tabulator.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

</head>
<body>
    @include('preloader')
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>
</html>