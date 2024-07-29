@extends('layouts.app') <!-- Adjust according to your layout -->

@section('content')
@include('layouts.navs')
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
</style>  
<div class="container d-flex align-items-center justify-content-center mb-3">
    <div class="ms-2 mt-5 col-md-4 w-100 h-100"> <!-- Start of announcement -->
        <h2 class="mt-5">Announcements</h2>
        <div class="row p-1 border rounded"><!-- Start of Card Container -->
            @foreach($announcements as $a_data)
                @php
                    $a_img = base64_decode($a_data->cover);
                    $a_title = base64_decode($a_data->title);
                    $a_content = base64_decode($a_data->content);
                    $creation_date = $a_data->date_created;
                @endphp
                <div class="col-md-4 mt-3">
                    <div class="shadow border rounded p-1">
                        <i><img class="img-fluid" src="{{ asset('announcement_img/' . $a_img) }}" alt="#"/></i>
                        <h3>{{ $a_title }}</h3>
                
                        <p style="font-size:13px;" class="m-0 p-0"><i><b>Date:</b> {{ \Carbon\Carbon::parse($a_data->created_at)->format('F d, Y h:i A') }}</i></p>
                        <div class="" style="text-align:justify;">
                            <p>{{ $a_content }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div><!-- End of Card Container -->

        <div class="mt-3">
            {{ $announcements->links() }}
        </div>
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

@endsection

@section('styles')
<style>
nav {
    background-color: rgba(0, 0, 0, 0.8);
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
@section('title', 'Announcements')