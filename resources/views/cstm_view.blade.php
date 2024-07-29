@extends('layouts.app')

@section('content')
    @include('layouts.navs')

    <div class="container d-flex align-items-center h-100 justify-content-center mb-3">
        <div class="ms-2 mt-5 col-md-4">
            <!-- Start of announcement -->
            <div class="row p-1 border rounded p-3" style="z-index:1; background-color:rgb(0,0,0,0.1);">
                {!! $pageViewHtml !!}
            </div>
            <!-- End of Card Container -->
        </div>
        <!-- End of Announcement -->
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
        .program-b-wrap {
            transition: .4s;
            border-radius: 10px;
            z-index: 2;
        }
        .program-b-wrap:hover {
            transition: .2s;
            transform: translateY(-10px);
            box-shadow: 0px 2px 5px 1px rgb(0,0,0,0.6) !important;
        }
        .program-box {
            transition: .4s;
            color: rgba(238, 225, 180, 1);
            height: 230px;
            width: 100%;
            background-size: cover !important;
            background-position: center !important;
        }
        .program-box:hover {
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
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
@endsection

