@extends('layouts.appres')

@section('content')
    <style>
        /* General Container Styling */
        .container {
            padding: 20px;
            max-width: 1200px; /* Limit maximum width for better readability */
            margin: auto;
        }

        /* Heading Styling */
        h2 {
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 700;
            color: #343a40;
            text-align: center;
        }

        /* Program Box Styling */
        .program-b-wrap {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            background-color: #fff; /* Ensure card background is white */
        }

        .program-b-wrap:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .program-box {
            position: relative;
            height: 200px; /* Slightly smaller height for a better fit */
            width: 100%;
            background-size: cover;
            background-position: center;
            border-radius: 15px 15px 0 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
        }

        .program-box h3 {
            margin: 0;
            font-size: 1.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            z-index: 1;
        }

        .program-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }

        .program-box div {
            position: relative;
            z-index: 1;
        }

        .program-b-wrap p {
            margin: 0;
            padding: 15px;
            font-size: 0.875rem;
            color: #495057;
            background-color: #f8f9fa;
            border-radius: 0 0 15px 15px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .program-box h3 {
                font-size: 1.25rem;
            }
            
            .program-b-wrap p {
                font-size: 0.75rem;
                padding: 10px;
            }
        }
    </style>

    <div class="container">
        <h2>Programs</h2>
        <p class="text-muted text-center">Click items to view content.</p>
        <div class="row">
            @foreach($result as $a_data)
                @php
                    $a_id = $a_data->id;
                    $a_img = base64_decode($a_data->cover);
                    $a_title = base64_decode($a_data->title);
                    $creation_date = $a_data->created_at->format('F d, Y \a\t h:i A'); 
                @endphp
                <div class="col-md-4 mb-4">
                    <a class="text-decoration-none" href="{{ url('user/view_program', ['id' => $a_id]) }}">
                        <div class="program-b-wrap">
                            <div class="program-box" style="background-image: url('{{ asset('program_upload_img/' . $a_img) }}');">
                                <div>
                                    <h3 class="text-center">{{ $a_title }}</h3>
                                </div>
                            </div>
                            <p><i><b>Date Posted:</b> {{ $creation_date }}</i></p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
@endsection

@section('title', 'View Program')
