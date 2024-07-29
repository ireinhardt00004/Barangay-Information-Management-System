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

    <meta name="description" content="{{ $generalConfig->meta_desc ?? 'Default description' }}">
    <meta property="og:title" content="{{ $generalConfig->title ?? 'Default Title' }}" />
    <meta property="og:description" content="{{ $generalConfig->meta_desc ?? 'Default description' }}" />
    <meta property="og:image" content="{{ asset('assets/head_logo/' . ($generalConfig->logo ?? 'default-logo.png')) }}" />
    <title>419 - Page Expired</title>
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


    <link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/js/tabulator.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">

    <script src="https://kit.fontawesome.com/fe516ea130.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .error-container {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }
        .logo-container {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        h1  {
            color: #333;
            font-size: 3em;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            font-size: 1.2em;
        }

        .home-link {
            text-decoration: none;
            color: #fff;
           /* background-color: #3498db;*/
           background-color: blue;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: inline-block;
        }
        
    </style>
</head>
<body>
    <div class="error-container">
        <div class="logo-container">
       <a href="/" title="Return to Homepage">
             <img style="width: 80px; height:85px; "  src="{{ asset('assets/head_logo/' . ($generalConfig->logo ?? 'default-logo.png')) }}" alt="logo">
         </a>
            <h5 class="mb-4">{{ $generalConfig->title ?? 'Default Title' }}</h5>
        </div>
        
        <h1>419 Page Expired</h1>
        <p>Sorry, Your session has expired. You need to re-login or </p>
        <a href="/" class="home-link">Go to Homepage</a>
    </div>
    <br>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
