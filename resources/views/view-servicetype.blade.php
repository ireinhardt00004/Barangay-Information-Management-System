@extends('layouts.app')

@section('content')
@include('layouts.navs')

    <div class="container d-flex align-items-center justify-content-center mb-3">
        <div class="ms-2 mt-5 col-md-4 w-100 h-100"> <!-- Start of announcement -->
            <h2 class="mt-5"><a href="{{ url('service-types') }}"><i class="fa-solid fa-left-long"></i> Go back to Service Type</a></h2>
            <div class="row p-1 rounded"><!-- Start of Card Container -->
                <div class="col-md-4 p-2">
                    <img draggable="false" class="img-fluid shadow cstm-img-box" src="{{ asset('service_type_imgs/' . $res->photo) }}" alt="{{ $res->request_type }}">
                </div>
                <div class="col">
                    <h1><b>{{ $res->request_type }}</b></h1>
                    <div>{!! $res->description !!}</div>
                </div>
            </div><!-- End of Card Container -->

            <!-- Start of Make a Request Link -->
            <div class="make-request mt-4 text-center">
                <a href="{{ route('requestfile.index') }}" class="btn btn-primary" title="You should log in first">Make a Request</a>
            </div>
            <!-- End of Make a Request Link -->
        </div><!-- End of Announcement -->
    </div>

    <footer>
        @foreach ($gens as $gen)
        <div class="f-head d-flex p-4 align-items-center">
          <img height="65" src="{{ asset('assets/head_logo/' . $gen->logo) }}" alt="Web Logo">
          <div class="ms-2 d-flex align-items-center">
            <p><b>{{ $gen->title }}</b></p>
          </div>
        </div>
        <div class="f-body container">
          <div class="row">
            <div class="col-md-2 text-center mb-3 mb-md-0">
              <img height="100" src="{{ asset('assets/head_logo/' . $gen->logo) }}" alt="Web Logo">
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
              <h6 class="fw-bold">Gov Links</h6>
              <ul class="list-unstyled">
                @foreach($footers as $footer)
                  @if(isset($footer['gov']))
                    @php
                      $govLink = $footer['gov'];
                      $govText = rtrim(str_replace(['http://', 'https://'], '', $govLink), '/');
                    @endphp
                    <li><a class="linkz-footer d-block mb-2" href="{{ $govLink }}">{{ $govText }}</a></li>
                  @endif
                @endforeach
              </ul>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
              <h6 class="fw-bold">Official Social Media Accounts</h6>
              <ul class="list-unstyled">
                @foreach($footers as $footer)
                  @if(isset($footer['social']))
                    @php
                      $socialLink = $footer['social'];
                      $socialText = rtrim(str_replace(['http://', 'https://'], '', $socialLink), '/');
                    @endphp
                    <li><a class="linkz-footer d-block mb-2" href="{{ $socialLink }}">{{ $socialText }}</a></li>
                  @endif
                @endforeach
              </ul>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
              <h6 class="fw-bold">Contact Us</h6>
              <ul class="list-unstyled">
                @foreach($footers as $footer)
                  @if(isset($footer['contact']))
                    @php
                      $contactLink = $footer['contact'];
                      $contactText = rtrim(str_replace(['http://', 'https://'], '', $contactLink), '/');
                    @endphp
                    <li><a class="linkz-footer d-block mb-2" href="{{ $contactLink }}">{{ $contactText }}</a></li>
                  @endif
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        @endforeach
      </footer>

    <style>
        nav {
            background-color: rgb(0,0,0,0.8);
        }
        .cstm-img-box {
            border: 1px solid rgb(0,0,0,0.3);
            border-radius: 10px;
            transition: .4s;
        }
        .cstm-img-box:hover {
            border: 1px solid rgb(0,0,0,0.1);
            box-shadow: 0px 5px 5px 1px rgb(0,0,0,0.5)!important;
            transition: .2s;
            transform: translateY(-5px);
        }
        .linkz-footer {
            overflow-wrap: break-word;
            word-break: break-all;
            display: block;
        }
        .f-body {
            padding: 0 15px;
        }
        @media (max-width: 768px) {
            .f-body {
                text-align: center;
            }
        }
        .make-request {
            margin-top: 15px;
            float:right;
        }
        .make-request a {
            font-size: 1.25rem;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
        }
        .make-request a.btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        .make-request a.btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
@endsection
