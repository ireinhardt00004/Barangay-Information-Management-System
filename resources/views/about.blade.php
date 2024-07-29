@extends('layouts.app') <!-- Adjust according to your layout -->

@section('content')
@include('layouts.navs')

<style>
    :root {
        --cover-op: 0.7;
    }
    .about-page {
        background: linear-gradient(rgba(0,0,0, var(--cover-op)), rgba(0, 0, 0, var(--cover-op))), url('./assets/imgs/bg-img1.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .about-page h1 {
        font-size: 3rem;
        color: white;
    }
    .about-page p {
        font-size: 1.2rem;
        color: white;
    }
    @media (max-width: 767.98px) {
        .about-page h1 {
            font-size: 2.5rem;
        }
        .about-page p {
            font-size: 1rem;
        }
    }
</style>

<div class="about-page">
    <div class="container">
        <div class="row">
            <div class="col-md-4 d-flex justify-content-center">
                <img height="200" src="{{ asset('assets/head_logo/' . ($gens->logo ?? 'default-logo.png')) }}">
            </div>
            <div class="col-md-7">
                <h1 class="mb-4">{{ $gens->about_title ?? 'About Us' }}</h1>
                <p>
                    {{ $gens->about_desc ?? 'Default description about the company or website.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<footer>
    @foreach ($footers as $footer)
    <div class="f-head d-flex p-4 align-items-center">
        <img height="65" src="{{ asset('assets/head_logo/' . $footer->logo) }}" alt="Web Logo">
        <div class="ms-2 d-flex align-items-center">
            <p><b>{{ $footer->title }}</b></p>
        </div>
    </div>
    <div class="f-body container">
        <div class="row">
            <div class="col-md-2 text-center mb-3 mb-md-0">
                <img height="100" src="{{ asset('assets/head_logo/' . $footer->logo) }}" alt="Web Logo">
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <h6 class="fw-bold">Gov Links</h6>
                <ul class="list-unstyled">
                    @if(isset($footer->gov_links))
                        @php
                            $govLinks = json_decode($footer->gov_links, true);
                        @endphp
                        @foreach($govLinks as $govLink)
                            @php
                                $govText = rtrim(str_replace(['http://', 'https://'], '', $govLink), '/');
                            @endphp
                            <li><a class="linkz-footer d-block mb-2" href="{{ $govLink }}">{{ $govText }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <h6 class="fw-bold">Official Social Media Accounts</h6>
                <ul class="list-unstyled">
                    @if(isset($footer->social_links))
                        @php
                            $socialLinks = json_decode($footer->social_links, true);
                        @endphp
                        @foreach($socialLinks as $socialLink)
                            @php
                                $socialText = rtrim(str_replace(['http://', 'https://'], '', $socialLink), '/');
                            @endphp
                            <li><a class="linkz-footer d-block mb-2" href="{{ $socialLink }}">{{ $socialText }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="fw-bold">Contact Us</h6>
                <ul class="list-unstyled">
                    @if(isset($footer->contact_links))
                        @php
                            $contactLinks = json_decode($footer->contact_links, true);
                        @endphp
                        @foreach($contactLinks as $contactLink)
                            @php
                                $contactText = rtrim(str_replace(['http://', 'https://'], '', $contactLink), '/');
                            @endphp
                            <li><a class="linkz-footer d-block mb-2" href="{{ $contactLink }}">{{ $contactText }}</a></li>
                        @endforeach
                    @endif
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

@section('title', 'About Us')
