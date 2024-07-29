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
    <link rel="shortcut icon" href="{{ asset('assets/head_logo/' . ($generalConfig->logo ?? 'default-logo.png')) }}" type="image/x-icon" />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.15/index.global.min.js'></script>
    <link rel="stylesheet" href="/js/Data Table/DataTables/datatables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
     
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    /* .fc-next-button {
        width: 1rem;
        padding: .5rem;
    }
    .fc-button-group{
        width: 50%;
    } */
    .fc-license-message{
        z-index: 888 !important;
    }
    .drawerContainer {
        position: fixed;
        height: 100%;
        width: 100%;
        background-color: transparent;
        z-index: 30;
        display: flex;

        justify-content: end;
        display: none;
    }

    .drawerContainerShow {
        display: flex;
    }

    .dimmer {
        position: fixed;
        height: 100%;
        width: 100%;
        background-color: black;
        opacity: 20%;
        z-index: 40;

    }

    .totheleft {
        transform: translateX(600px);
    }

    /* .notifMessResizer {
        height: 400px;
    } */

    button {
        cursor: pointer;
    }

    input {
        cursor: pointer;
    }

    .messClose {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .messClose>span {
        padding: .4rem;
        cursor: pointer;

    }

    .messClose>span:hover {
        background: #dddcdc;
    }

    .MessDaw {
        background-color: #fff;
        border-radius: 0.5rem;
        overflow-y: auto;
        display: block;
        z-index: 50;
        width: 50%;
        height: 20%;
        display: none;
    }

    .MessDaw>div {
        padding: 1rem;
    }

    .MessDawHide {
        width: 50%;
        position: absolute;
        background-color: #fff;
        /* Corrected capitalization */
        top: 6%;
        height: 40%;
        left: 45%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        /* Used rgba for transparency */
        border-radius: 0.5rem;
        overflow-y: auto;
        display: block;
    }

    .notifClose {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notifClose>span {
        padding: .4rem;
        cursor: pointer;
    }

    .notifClose>span:hover {
        background: #dddcdc;
    }

    .notifDaw {
        background-color: #fff;
        border-radius: 0.5rem;
        overflow-y: auto;
        z-index: 50;
        width: 50%;
        height: 20%;
        display: none;
    }

    .notifDaw>div {
        padding: 1rem;
    }

    .notifDawHide {
        width: 50%;
        position: absolute;
        background-color: #fff;
        top: 6%;
        height: 40%;
        left: 48%;
        /* Corrected capitalization */
        height: 40%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        /* Used rgba for transparency */
        border-radius: 0.5rem;
        overflow-y: auto;
        display: block;
    }

    .innerMain>* {
        transition: all .1s .1s ease-in-out;
    }

    * {
        padding: 0%;
        margin: 0%;
        box-sizing: border-box;
        overflow: hidden;
        font-family: "Poppins", sans-serif;
    }

    html {
        height: 100dvh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    body {
        height: 100%;
        /* Full viewport height */
        display: flex;
        flex-direction: column;
        overflow: hidden;
        /* Consider removing this to allow body scrolling */
    }

    #app {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .headerOne {
        width: 100%;
        background-color: #E2c868;
        display: flex;
        justify-content: start;
    
        box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);

    }

    .burgerButton {
        height: 100%;
        margin-right: 1rem;
        background-color: #edd8a4;
        display: flex;
        justify-content: center;
        align-items: center;
        border-top-right-radius: 0.5rem;
        /* 8px */
        border-bottom-right-radius: 0.5rem;
        /* 8px */

    }

    .burgerButton>button {
        border: none;
        padding: .5rem .5rem;
        text-align: center;
        width: 5em;
        height: 70%;
        background-color: transparent;

    }

    .burgerButton:hover {
        opacity: 40%;
    }

    .logoHeader {
        display: flex;
        align-items: center;
        gap: 15px;
        
    }

    .logoHeader>a svg {
        height: 3rem;
        width: 3rem;
        object-fit: contain;
        border-radius: 9999px
    }

    .logoHeader>a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: white;
        font-size: base;
        gap: 1rem;
    }

    .sysTitle {
        display: flex;
        flex-direction: column;
        align-items: start;
        color: white;
    }
    .sysTitle p {
        margin: 0px;
    }

    .bodymain {
        display: grid;
        grid-template-columns: 25% 75%;
        height: 100%;
        /* Adjust height based on your layout needs */
        background-color: #f0eceb;
        position: relative;
       
    }

    .bodymain aside {
        width: 100%;
        height: 100%;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
        background-color: #ffff;
        transition: all .2s .2s ease-in-out;
    }

    .imgBox {
        width: 100%;
        height: 20%;
        position: relative;
        display: flex;
        justify-content: start;
        align-items: center;
    }

    .imgBox p {
        margin: 0px;
    }

    .imgBox>img {
        position: absolute;
        object-fit: cover;
        object-position: center;
        width: 100%;
        height: 100%;
        z-index: 0;
        opacity: 95%;
    }

    .imgBox>p {
        z-index: 20;
        position: absolute;
        color: white;
        align-self: flex-end;
        place-self: end;
        padding: 1rem 1rem;

    }

    .mainNavigationTitle>h2 {
        font-size: 1rem;
        /* 16px */
        line-height: 1.5rem;
        /* 24px */
        padding: 0px 1rem;
        font-weight: bold;
        margin-top: 1rem;
        margin-bottom: .5rem;
    }

    .vignette {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        box-shadow: 0 0 200px rgba(0, 0, 0, 0.7) inset;
    }

    .mainNavigation {
        font-size: 1rem;
        /* 16px */
        line-height: 1.5rem;
        /* 24px */
        padding: 0px 1rem;
    }

    .navLinks>li>a {
        display: flex;
        justify-content: start;
        align-items: center;
        gap: 1.75rem;
        margin-top: .50rem;
        text-decoration: none;
        color: black;
        padding: .50rem .60rem;
        border-radius: 0.5rem;
    }

    .navLinks>li>a.active {
        background-color: #edd8a4;
    }

    .navLinks>li>a:hover {
        background: #dddcdc;
    }

    .mainNavigation .navLinks svg {
        height: 2rem;
        width: 2rem;
        object-fit: contain;
        border-radius: 9999px;
    }

    .innerMain {
        background-color: transparent;
        overflow-y: auto;
        /* Enable vertical scrolling */
        height: 100%;

    }


    .innerMain>.section-1 {
        display: flex;
        justify-content: start;
        width: 100%;
        margin-bottom: .75rem;
        gap: .50rem;
        border-radius: .5rem;


    }

    .section-1>form {
        width: 100%;
        height: 100%;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: 0.5rem;
        gap: 1rem;
        justify-content: space-between;
    }

    .section-1>form .formDiv {
        display: flex;
        justify-content: space-evenly;
    }

    .section-1>form input[type='text'] {
        padding: 1rem 1.5rem;
        border-radius: .5rem;
        border: none;
        background-color: #dddcdc;
    }

    .section-1>form input[type='text']::placeholder {
        color: rgb(70, 70, 70);
        font-size: 1rem;
    }

    .section-1>form>.formDiv button {
        padding: .70rem .70rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        border: none;
        border-radius: .5rem;
        background-color: transparent;
        transition: all;
    }

    .section-1>form>.formDiv button:hover {
        background-color: #dddcdc;

    }

    .section-1>form>.formDiv>button svg {
        width: 1.50rem;
        height: 1.50rem;
    }

    .section-1>form>.formDiv>.fileSelect svg {
        width: 1.50rem;
        height: 1.50rem;
    }

    .section-1>form>.formDiv>.fileSelect {
        padding: .70rem .70rem;
        display: flex;
        justify-content: start;
        align-items: center;
        gap: 1rem;
        border: none;
        border-radius: .5rem;
    }

    .section-1>form>.formDiv>.fileSelect:hover {
        background-color: #dddcdc;
    }

    .notifMess {
        background-color: transparent;
        display: flex;
        align-items: end;
        position: relative;
        margin-left: auto;
        
    }

    .notifMess>div {
        display: flex;
        gap: .75rem;
        align-items: end;
        justify-content: end;
        padding: 1px .75rem;

    }

    .notifMess>div button {
        padding: .75rem .75rem;
        border: none;
        border-radius: .5rem;
        background-color: transparent;
    
    }

    .notifMess>div button:hover {
        opacity: 70%;
        outline: rgb(44, 44, 44) 1px solid;
    }

    .notifMess>div>button svg {
        width: 1.50rem;
        height: 1.50rem;
    }

    .Requestsection {
        padding: .75rem;
        width: 100%;
        gap: 1rem;
        border-radius: .5rem;
        background-color: white;
        padding: 1rem .75rem;
        margin-bottom: .75rem;
    }

    .calendar {
        padding: .75rem;
        width: 100%;
        height: 100%;
        gap: 1rem;
        border-radius: .5rem;
        background-color: white;
        padding: 1rem .75rem;
        display: flex;
        margin-bottom: .75rem;
    }

    #calendar {
        padding: 1rem;
        width: 100%;
        height: 100%;
    }

    .chat {
        padding: 1.75rem;
        width: 100%;
        height: 100%;
        gap: 1rem;
        border-radius: .5rem;
        background-color: white;
        padding: 1rem .75rem;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        margin-bottom: .75rem;

    }

    #myChart {
        padding: 2rem !important;
    }

    .Totalevents {

        padding: 1rem;
        width: 60%;
        height: 95%;
        background-color: #dddcdc;
        border-radius: .5rem;
        overflow-y: auto;

    }

    /* Hide scrollbar for Chrome, Safari, and Opera */
    .example::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge, and Firefox */
    .example {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .tulongPost {
        padding: 2.75rem;
        width: 100%;
        height: 70%;
        gap: 1rem;
        border-radius: .5rem;
        background-color: white;
        padding: 1rem .75rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: .75rem;
    }

    .tulongtext {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        text-justify: auto;
        overflow-y: auto;
    }

    .tulongpara {
        height: 100%;
        width: 100%;
        overflow-y: auto;
        padding: 1.50rem;
    }

    .tulongpara>h1 {
        font-size: 1.25rem;
    }

    .tulongpara>p {
        font-size: 1rem;
    }

    .tulongimgs {
        width: 100%;
        height: 70%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-justify: auto;
        overflow-x: auto;
        gap: .5rem;
        background: #dddcdc;
        justify-self: start;
        align-self: self-start;
        border-radius: .5rem;
        box-shadow: -9px 10px 17px -6px rgba(0, 0, 0, 0.18);
    }

    .tulongimgs>img {
        object-fit: cover;
        object-position: center;
        border-radius: .5rem;
        width: 100%;
        height: 100%;
        flex-shrink: 0;
        padding: .2rem;
    }

    .tulongButton {
        width: 100%;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .tulongButton>button {
        padding: .75rem;
        border: none;
        background-color: transparent;
        border-radius: .5rem;
    }

    .tulongButton>button:hover {
        background-color: #dddcdc;
    }

    .tulongButton>button svg {
        width: 1.75rem;
        height: 1.75rem;
    }

    .section-1>.notifMess>div #myTable {
        overflow-x: auto;
    }

    .hideSide {
        width: 0% !important;
        background: red;
        /* display: none; */

    }

    .bodymainHide {
        display: grid;
        grid-template-columns: 0% 100%;
        height: 100dvh;
        /* Adjust height based on your layout needs */

        background-color: #f0eceb;
    }

    /* mediaquery */

    @media only screen and (min-width: 320px) and (max-width: 767px) {
        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 1.5em;
            height: 40%;
        }

        .fc .fc-toolbar {
            flex-direction: column;
            gap: .5rem;
        }
        .notification-container {
                        position: fixed;
                        top: 50px;
                        right: 0;
                        width: 100% !important;
                        max-height: 80vh; /* Adjust this value as needed */
                        background: white;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        border-radius: 5px;
                        z-index: 1000;
                        overflow-y: auto;
                        font-size: .8rem;
                    }
        html {
            height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        body {
            height: 100%;
            /* Full viewport height */
            display: flex;
            flex-direction: column;
            overflow: hidden;
            /* Consider removing this to allow body scrolling */
        }

        .burgerButton {
            height: 100%;
            margin-right: 1rem;
            background-color: #edd8a4;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top-right-radius: 0.5rem;
            /* 8px */
            border-bottom-right-radius: 0.5rem;
            /* 8px */
        }

        .burgerButton>button {
            border: none;
            padding: .5rem .5rem;
            text-align: center;
            width: 3.50em;
            height: 100%;
            background-color: transparent;

        }

        .logoHeader>a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            font-size: small;
            gap: 1rem;
        }

        #myChart {
            padding: .50rem !important;
        }

       .notifMess>div button {
            padding: .5rem .75rem;
            border: none;
            border-radius: .5rem;
            background: transparent;
        }

        .sysTitle {
            display: flex;
            flex-direction: column;
            align-items: start;
            color: white;
            font-size: small;
        }

        .innerMain {
            background-color: transparent;
            overflow-y: auto;
            /* Enable vertical scrolling */

        }

        .bodymain aside {
            width: 100%;
            height: 100%;

        }

        .bodymain {
            display: grid;
            grid-template-columns: 0% 100%;
            height: 100%;
            /* Adjust height based on your layout needs */
            background-color: #f0eceb;
            position: relative;

        }

        .bodyHideMobile {
            position: absolute;
            height: 100%;
            width: 50%;
            left: 0;
            z-index: 50px;
            transition: .2s .2s ease-in-out;

        }

        .innerMain>.section-1 {
            display: flex;
            flex-direction: column-reverse;
            justify-content: start;
            width: 100%;
            margin-bottom: .75rem;
            gap: .50rem;

        }

        .notifMess {
            background-color: transparent;
            display: flex;
            flex-direction: column;
            align-items: end;
            position: relative;
        }

        /* .section-1>.notifMess>div {
        display: flex;
        width: 100%;
        gap: .75rem;
        align-items: end;
        justify-content: end;
        padding: 1px .75rem;
        height: 100%;
    } */
        .section-1>form .formDiv {
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            height: 100%;
        }

        .section-1>form>.formDiv button {
            padding: .70rem .70rem;
            display: flex;
            justify-content: start;
            align-items: center;
            gap: 1rem;
            border: none;
            border-radius: .5rem;
            background-color: transparent;
            transition: all;
        }

        .section-1>form {
            width: 100%;
        }

        .MessDaw {
            position: absolute;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            /* Used rgba for transparency */
            border-radius: 0.5rem;
            overflow-y: auto;
            display: none;
        }

        .MessDaw>div {
            padding: 1rem;
        }

        .MessDawHide {
            width: 80%;
            position: absolute;
            background-color: #fff;
            /* Corrected capitalization */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            /* Used rgba for transparency */
            border-radius: 0.5rem;
            overflow-y: auto;
            display: block;
            left: 5%;
            height: 40%;
            top: 5%;

        }

        .notifDaw {
            position: absolute;
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            border-radius: .5rem;
            display: none;
            overflow-y: auto;
        }

        /* .notifMessResizer {
            height: 400px;
        } */

        .notifDaw>div {
            padding: 1rem;
        }

        .notifDawHide {
           width: 80%;
            position: absolute;
            background-color: #fff;
            /* Corrected capitalization */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            /* Used rgba for transparency */
            border-radius: 0.5rem;
            overflow-y: auto;
            display: block;
            left: 15%;
            height: 40%;
            top: 5%;
        }
        .chat {

            width: 100%;
            height: 100%;
            gap: 1rem;
            border-radius: .5rem;
            background-color: white;
            padding: 1rem .75rem;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;

        }

        .tulongPost {
            padding: 2.75rem;
            width: 100%;
            height: 70%;
            gap: 1rem;
            border-radius: .5rem;
            background-color: white;
            padding: 1rem .75rem;
            display: flex;
            flex-direction: column-reverse;
            justify-content: center;
            align-items: center;
            margin-bottom: .75rem;
        }

        .tulongtext {
            width: 100%;
            height: 90%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-justify: auto;
            overflow-y: auto;
        }

        .tulongpara>h1 {
            font-size: .75rem;
        }

        .tulongpara>p {
            font-size: .65rem;
        }

        .tulongpara {
            height: 100%;
            width: 100%;
            overflow-y: auto;
            padding: .7rem;
        }

        .tulongimgs {

            display: flex;

            justify-content: space-between;
            background: #dddcdc;
            gap: .50rem;
            padding: .75rem;
            border-radius: .5rem;
            width: 100%;
            height: 100%;
            overflow-x: auto;
        }

        .tulongimgs>img {
            object-fit: cover;
            object-position: center;
            border-radius: .5rem;
            width: 100%;
            height: 100%;
            flex-shrink: 0;
        }
         .navLinks{
            padding: 0px;
            font-size: .8rem;
        }
    }
    @media only screen and (min-width: 768px) and (max-width:1023px){
         #aside>.mainNavigation{
            padding: 0%;
         }

        #aside>.mainNavigation>.navLinks li a{
            font-size: .8rem;
            
        }
        .navLinks>li>a {
        color: black;
        padding: 0%;
        }

        .navLinks{
            padding: 0px;
            margin-left: .5rem;
        }
        
    }
</style>

<body>
    @include('preloader')
    <div id="app">
       
     @include('layouts.header-res')

        <main class="bodymain" id="bodymain">
            @include('layouts.aside')
            
            <div id="innerMain" class="innerMain">
                @yield('content')
            </div>

        </main>
    </div>

</body>

<script src="{{ asset('js/chart.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable({
            scrollY: 400
        });

    });
</script>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const offcanvas = document.getElementById('offcanvas');
        const aside = document.getElementById('aside');
        const bodymain = document.getElementById('bodymain')
        const notifDawBTN = document.getElementById('notifDawBTN')
        const MessDawBTN = document.getElementById('MessDawBTN')
        const MessDaw = document.getElementById('MessDaw')
        const notifDaw = document.getElementById('notifDaw')
        const notifClose = document.getElementById('notifClose')
        const closeMess = document.getElementById('closeMess')
        const notifMess = document.getElementById('notifMess')
        const drawerContainer = document.getElementById('drawerContainer')
        const dimmer = document.getElementById('dimmer')

        offcanvas.addEventListener('click', () => {
            aside.classList.toggle('bodyHideMobile')
            bodymain.classList.toggle('bodymainHide')
            console.log(aside.classList)
        })

        notifDawBTN.addEventListener('click', () => {


            MessDaw.classList.toggle('MessDawHide')
            drawerContainer.classList.toggle('drawerContainerShow')



        })

        MessDawBTN.addEventListener('click', () => {
            notifDaw.classList.toggle('notifDawHide')
            drawerContainer.classList.toggle('drawerContainerShow')
        })

        notifClose.addEventListener('click', () => {
            notifDaw.classList.toggle('notifDawHide')
            drawerContainer.classList.toggle('drawerContainerShow')

        })
        closeMess.addEventListener('click', () => {
            MessDaw.classList.toggle('MessDawHide')
            drawerContainer.classList.toggle('drawerContainerShow')
        })
        dimmer.addEventListener('click', () => {
            drawerContainer.classList.remove('drawerContainerShow')
            notifDaw.classList.remove('notifDawHide')
            MessDaw.classList.remove('MessDawHide')
        })
    })
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notifDawBTN = document.getElementById('notifDawBTN');
        const notifDaw = document.getElementById('notifDaw');
        const notifClose = document.getElementById('notifClose');
        const drawerContainer = document.getElementById('drawerContainer');

        function toggleNotificationDrawer() {
            notifDaw.classList.toggle('notifDawHide');
            drawerContainer.classList.toggle('drawerContainerShow');
        }

        function closeNotificationDrawer() {
            notifDaw.classList.add('notifDawHide');
            drawerContainer.classList.remove('drawerContainerShow');
        }

        notifDawBTN.addEventListener('click', toggleNotificationDrawer);
        notifClose.addEventListener('click', closeNotificationDrawer);

        // Close notification drawer when clicking outside
        window.addEventListener('click', (event) => {
            if (!notifDaw.contains(event.target) && !notifDawBTN.contains(event.target)) {
                closeNotificationDrawer();
            }
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<!-- <script>
    window.addEventListener('DOMContentLoaded', () => {
        const innerMain = document.getElementById('innerMain');
        const observer = new IntersectionObserver((entry)=>{
            entry.forEach((item)=>{
                item.target.classList.toggle('totheleft', !item.isIntersecting)
            })
        }, {
            threshold: 0.5,
            root: innerMain,
            rootMargin: "200px"
        })

          Array.from(innerMain.children).forEach((item) => {
           observer.observe(item)
        })
    })
</script>

</html>