@extends('layouts.appres')

@section('content')
    <style>
        /* Container styling */
        .container {
            padding: 20px;
        }
        
        /* Program Box Styling */
        .program-b-wrap {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .program-b-wrap:hover {
            transform: translateY(-8px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }

        .program-box {
            position: relative;
            height: 230px;
            width: 100%;
            background-size: cover;
            background-position: center;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
        }

        .program-box h3 {
            margin: 0;
            font-size: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }

        .program-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .program-box div {
            position: relative;
            z-index: 2;
        }

        .program-b-wrap p {
            margin: 0;
            padding: 10px;
            font-size: 0.875rem;
            color: #6c757d;
            background-color: #f8f9fa;
            border-radius: 0 0 10px 10px;
            text-align: center;
        }

        /* Heading and Text Styling */
        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 700;
            color: #333;
        }

        p {
            font-size: 1rem;
            color: #666;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .program-box h3 {
                font-size: 1.25rem;
            }
            
            .program-b-wrap p {
                font-size: 0.75rem;
            }
        }

        /* Footer and Additional Styles */
        .linkz-footer {
            display: block;
            color: #007bff;
            text-decoration: none;
            word-break: break-word;
        }

        .linkz-footer:hover {
            text-decoration: underline;
        }
    </style>

<div class="container d-flex align-items-center justify-content-center mb-3">
    <div class="ms-2 mt-5 col-md-4 w-100 h-100"> <!-- Start of announcement -->
        <h2 class="mt-5"><a href="{{ url('user/programs') }}"><i class="fa-solid fa-left-long"></i> Go back</a></h2>
        <div class="row p-1 rounded"><!-- Start of Card Container -->
            <div class="col-md-4 p-2">
                <img draggable="false" class="img-fluid shadow cstm-img-box" src="{{ asset('program_upload_img/' . $res->cover) }}" alt="{{ $res->title }}">
            </div>
            <div class="col">
                <h1><b>{{ $res->title }}</b></h1>
                <div>{!! $res->content !!}</div>
            </div>
        </div><!-- End of Card Container -->
    </div><!-- End of Announcement -->
</div>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
@endsection

@section('title', 'Programs')
