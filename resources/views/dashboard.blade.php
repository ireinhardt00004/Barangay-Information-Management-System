@extends('layouts.app')
@section('content')

<div class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark">
       <button class="border-0 btn order-lg-0 ms-2 m-0 me-lg-0 text-black" style="background-color:rgb(255, 245, 153)!important;" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
       <a class="ms-3 navbar-brand d-flex align-items-center text-white fw-bold" href="home">
          <img height="50" src="{{--logo --}}" alt="Logo">
          <div class="d-md-block ps-2">
             <p class="mb-0 cstm-text-sm" style="font-size: 14px">title</p>
          </div>
       </a>
    </nav>
    <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav" style="background-color:white;border-right:1px solid rgb(0,0,0,0.2);" id="sidenavAccordion">
       <div class="sb-sidenav-menu">
          <div class="nav">
             <div>
                <img class="img-fluid" src="./assets/imgs/bg-img1.png">
             </div>
             <div class="sb-sidenav-menu-heading">Main Navigation</div>
             <a class="nav-link" href="admin&v=home">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fas fa-house-chimney-window"></i></div>
                Dashboard
             </a>
             <a class="nav-link" href="admin&v=staff">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-users"></i></div>
                Staff
             </a>
             <a class="nav-link" href="admin&v=resident">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-users"></i></div>
                Resident
             </a>
             <a class="nav-link" href="admin&v=settings">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-gears fa-rotate-90"></i></div>
                Settings
             </a>
             <div class="sb-sidenav-menu-heading">Monitoring Request</div>
             <a class="nav-link" href="admin&v=mon_logs">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-regular fa-paste"></i></div>
                Requests logs
             </a>
             <a class="nav-link" href="admin&v=all_logs">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-shoe-prints"></i></div>
                All logs
             </a>
             <div class="sb-sidenav-menu-heading">Website</div>
             <a class="nav-link" href="admin&v=verifies">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-user-check"></i></div>
                Validate Accounts
             </a>
             <a class="nav-link" href="admin&v=records">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
                Records
             </a>
             <a class="nav-link" href="admin&v=reports">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
                Reports
             </a>
             <a class="nav-link" href="admin&v=programs">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-code-pull-request"></i></div>
                Programs
             </a>
             <a class="nav-link" href="admin&v=messages">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-regular fa-comments"></i></div>
                Messages
             </a>
             <a class="nav-link collapsed" id="news_anoucement" href="#" data-bs-toggle="collapse" data-bs-target="#cms_dropdown" aria-expanded="false" aria-controls="cms_dropdown">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
                Content Management
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
             </a>
             <div class="collapse news_anoucement" id="cms_dropdown" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav" style="background-color:rgb(238, 216, 137,0.4);">
                    <a class="nav-link" href="admin&v=cms_general">General</a>
                    <a class="nav-link" href="admin&v=cms_navs">Nav links</a>
                    <a class="nav-link" href="admin&v=cms_pages">Pages</a>
                    <a class="nav-link d-none" href="admin&v=cms_new_page"></a>
                    <a class="nav-link d-none" href="admin&v=cms_edit_page"></a>
                </nav>
             </div>
             <a class="nav-link" href="admin&v=news_announcement">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-bullhorn"></i></div>
                Announcement
             </a>
             <a class="nav-link collapsed" id="monr" href="#" data-bs-toggle="collapse" data-bs-target="#monr2_dropdown" aria-expanded="false" aria-controls="monr2_dropdown">
                <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-code-pull-request"></i></div>
                Monitoring Request
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
             </a>
             <div class="collapse monr" id="monr2_dropdown" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav" style="background-color:rgb(238, 216, 137,0.4);">
                    <!-- <a class="nav-link" href="admin&v=monr_wir">Walk in request</a> -->
                    <a class="nav-link" href="admin&v=monr_pr">Pending Request</a>
                    <a class="nav-link" href="admin&v=monr_ar">Approved Request</a>
                    <a class="nav-link" href="admin&v=monr_dr">Decline Request</a>
                </nav>
             </div>
             <br>
          </div>
       </div>
       <div class="sb-sidenav-footer">
          <div class="dropdown">
             <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
             <img src="./assets/imgs_uploads/" alt="" width="20" height="20" class="rounded-circle me-2">
             <strong style="width:auto;">{{ Auth::user()->email }}</strong>
             </a>
             <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
                <li><a class="dropdown-item" href="admin&v=settings">Settings</a></li>
                <li>
                   <hr class="dropdown-divider">
                </li>
                <li>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                    </form>
                    <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign out
                    </a>
                </li>                
             </ul>
          </div>
    </nav>
    </div>
    <div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
       <main>
          <div class="container-fluid px-4">
             <div class="d">
             <!-- <i class="cstm-colorz me-1 fa-solid fa-house"></i> -->
                <h1 class="mt-4">Dashboard | <span style="font-size:22px;">fgfd</span></h1>
             </div>
             <hr style="border:1px solid black;">
                {{-- <?php include($view);?> --}}
       </main>
       <footer class="py-4 bg-light mt-auto">
       <div class="container-fluid px-4">
       <div class="d-flex align-items-center justify-content-between small">
       <div class="text-muted">Copyright &copy; Brgy Tres Cruses 2023</div>
       <div>
       <!-- <a href="#">Privacy Policy</a>
       &middot;
       <a href="#">Terms &amp; Conditions</a> -->
       </div>
       </div>
       </div>
       </footer>
       </div>
       </div>
       <script src="./assets/js/sb-script.js"></script>
       <link href="./assets/css/sb-style.css" rel="stylesheet" />
    </div>
    <script>
    const navLinks = document.querySelectorAll('.nav-link');
    
    const uri = window.location.href;
    const uriParts = uri.split('=');
    const uriValue = uriParts[uriParts.length - 1];
    
    // navLinks.forEach(link => {
    //    const href = link.getAttribute('href');
    //    const hrefParts = href.split('=');
    //    const linkValue = hrefParts[hrefParts.length - 1];
    //    if (linkValue === uriValue) {
    //       link.classList.add('active-nav');
    //       let parent = link.parentNode;
    //       if(parent){
    //          p = parent.parentNode;
    //          p.classList.add('show');
    //          pp = document.getElementById("monr");
    //          pp.setAttribute('aria-expanded', 'true');
    //          pp.classList.remove('collapsed');
    //          pp.classList.add('active-parent');
    //       }
    //    }
    // });
    navLinks.forEach(link => {
       const href = link.getAttribute('href');
       const hrefParts = href.split('=');
       const linkValue = hrefParts[hrefParts.length - 1];
       if (linkValue === uriValue) {
          link.classList.add('active-nav');
          let parent = link.parentNode;
          const mon_reqs = ['monr_wir','monr_pr','monr_ar','monr_dr','cms_navs','cms_pages','cms_general','cms_new_page'];
          if(parent && mon_reqs.includes(linkValue)){
             p = parent.parentNode;
             let e_id = '';
             if(p.classList.contains('news_anoucement')){
                e_id = "news_anoucement";
             }else if(p.classList.contains('monr')){
                e_id = 'monr';
             }
             p.classList.add('show');
             pp = document.getElementById(e_id);
             pp.setAttribute('aria-expanded', 'true');
             pp.classList.remove('collapsed');
             pp.classList.add('active-parent');
          }
       }
    });
    </script>
    
    <link rel="stylesheet" type="text/css" href="./assets/style.css">
@endsection