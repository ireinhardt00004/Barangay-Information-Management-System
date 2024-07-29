@extends('layouts.app')

@section('content')
    @include('layouts.navs')

    <style>
        .service-b-wrap {
            transition: .4s;
            border-radius: 10px;
            z-index: 2;
        }
        .service-b-wrap:hover {
            transition: .2s;
            transform: translateY(-10px);
            box-shadow: 0px 2px 5px 1px rgb(0,0,0,0.6) !important;
        }
        .service-box {
            transition: .4s;
            color: rgba(238, 225, 180, 1);
            height: 230px;
            width: 100%;
            background-size: cover !important;
            background-position: center !important;
        }
        .service-box:hover {
            transition: .2s;
            transform: translateY(-10px);
        }
        nav {
            background-color: rgb(0,0,0,0.8);
        }
        .cstm-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .cstm-card-content {
            padding: 15px;
        }
        .cstm-card-content p, .linkz-footer {
            max-height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            word-wrap: break-word;
            line-height: 1.5em;
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
    </style>

    <div class="container d-flex align-items-center justify-content-center mb-3">
        <div class="ms-2 mt-5 col-md-4 w-100 h-100"> <!-- Start of service type -->
            <h2 class="mt-5">Service Types</h2>
            <div class="row p-1 border rounded" style="z-index:1; background-color:rgb(0,0,0,0.1);"><!-- Start of Card Container -->
                <p class="m-0" style="color:rgb(0,0,0,0.4);">Click image to view the type of services..</p>
               {{-- TRACK YOUR CODE --}}
                <a class="col-md-4 mt-3 text-decoration-none" href="{{route('track-request.index')}}">
                  <div class="shadow service-b-wrap">
                      <div class="service-box d-flex align-items-center justify-content-center border rounded p-1" style="background: linear-gradient( rgba(0,0,0, 0.5), rgba(0,0,0, 0.6)), url('{{ asset('sys_logo/request-icon.png') }}');">
                          <div>
                            <i style="font-size:25px;" class="fa-solid fa-magnifying-glass-location"></i>
                              <h3 class="text-center">Track Request</h3>
                          </div>
                      </div>
                          </div> 
              </a>
               {{-- REPORT SOMETHING --}}
               <a class="col-md-4 mt-3 text-decoration-none" href="{{route('file-report.index')}}">
                <div class="shadow service-b-wrap">
                    <div class="service-box d-flex align-items-center justify-content-center border rounded p-1" style="background: linear-gradient( rgba(0,0,0, 0.5), rgba(0,0,0, 0.6)), url('{{ asset('sys_logo/request-icon.png') }}');">
                        <div>
                          
                          <i style="font-size:25px;" class="fa-solid fa-bug"></i>
                            <h3 class="text-center">File a Report</h3>
                        </div>
                    </div>
                  </div> 
            </a>
                @foreach($result as $a_data)
                    @php
                        $a_id = $a_data->id;
                        $a_img = base64_decode($a_data->photo);
                        $a_title = base64_decode($a_data->request_type);
                        $a_content = base64_decode($a_data->description);
                        $creation_date = $a_data->created_at->format('F d, Y \a\t h:i A'); 
                    @endphp
                    <a class="col-md-4 mt-3 text-decoration-none" href="{{ url('view_service_type', ['id' => $a_id]) }}">
                        <div class="shadow service-b-wrap">
                            <div class="service-box d-flex align-items-center justify-content-center border rounded p-1" style="background: linear-gradient( rgba(0,0,0, 0.5), rgba(0,0,0, 0.6)), url('{{ asset('service_type_imgs/' . base64_decode($a_data->photo)) }}');">
                                <div>
                                    <h3 class="text-center">{{ $a_title }}</h3>
                                </div>
                            </div>
                            {{-- <p style="font-size:13px;" class="mx-1 p-0 text-muted"><i><b>Date Posted:</b> {{ $creation_date }}</i></p>--}}
                        </div> 
                    </a>
                @endforeach
            </div><!-- End of Card Container -->
        </div><!-- End of Service Type -->
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
                  @if(isset($footer->gov))
                    @php
                      $govLink = $footer->gov;
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
                  @if(isset($footer->social))
                    @php
                      $socialLink = $footer->social;
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
                  @if(isset($footer->contact))
                    @php
                      $contactLink = $footer->contact;
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
      
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
@endsection

@section('title','Service Types')
