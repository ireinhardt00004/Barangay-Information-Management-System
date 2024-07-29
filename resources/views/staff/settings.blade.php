@extends('layouts.app')
@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4 mb-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Settings</span></h1>
            </div>
            <hr style="border:1px solid black;">

            <div class="container-fluid px-4">
                <div id="table-container" style="font-size:18px;">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('adminprofile.update') }}">
                        @csrf
                        <div class="row">
                            <!-- User Photo Section -->
                            <div class="col-md-4 text-center">
                                <img class="img-fluid rounded-circle" id="alb-prev-img" height="200" width="200" src="{{ asset('profile_pic/' . ($user_photo ?? 'default.jpg')) }}" alt="User Photo">

                                <!-- Image Upload Button -->
                                <label for="imageFilez-pr" class="btn btn-outline-primary mt-2 w-75">
                                    <input onchange="sel_imgv2('alb-prev-img','imageFilez-pr');" class="d-none" type="file" id="imageFilez-pr" name="profile_pic" accept="image/gif, image/png, image/jpeg">
                                    Upload Image
                                </label>

                                <!-- Save Changes Button -->
                                <button class="btn btn-success mt-2 w-75" name="save_settings" type="submit">Save Changes</button>
                            </div>

                            <!-- User Information Section -->
                            <div class="col-md-8">
                                <div class="form-floating mb-3">
                                    <input type="text" name="fname" value="{{ old('fname', $firstname) }}" id="name_box3" class="form-control" placeholder="Firstname">
                                    <label for="name_box3">Firstname:</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="middlename" value="{{ old('middlename', $middlen) }}" id="name_box1" class="form-control" placeholder="Middlename">
                                    <label for="name_box1">Middlename:</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="lname" value="{{ old('lname', $lastname) }}" id="name_box2" class="form-control" placeholder="Lastname">
                                    <label for="name_box2">Lastname:</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" name="email" value="{{ old('email', $email) }}" id="name_box4" class="form-control" placeholder="Email">
                                    <label for="name_box4">Email:</label>
                                </div>
                                <p class="text-muted">Leave password empty if you don't wish to change it.</p>
                                <div class="form-floating mb-3">
                                    <input type="password" name="password" id="pass_box" class="form-control" placeholder="Reset Password">
                                    <label for="pass_box">Reset Password:</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- JavaScript -->
<script>
function sel_imgv2(eid, iid) {
    var file_path = document.getElementById(iid);
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').pop().toLowerCase();
    if (['jpg', 'gif', 'png', 'jpeg'].includes(f_ext)) {
        preview.src = URL.createObjectURL(file_path.files[0]);
        preview.classList.remove('cstm-hidden');
    }
}

// SweetAlert for success and error messages
@if (session('status'))
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('status') }}',
        confirmButtonText: 'OK'
    });
@endif

@if (session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonText: 'OK'
    });
@endif
</script>
<script>
    const navLinks = document.querySelectorAll('.nav-link');
    
    const uri = window.location.href;
    const uriParts = uri.split('=');
    const uriValue = uriParts[uriParts.length - 1];
    
    navLinks.forEach(link => {
       const href = link.getAttribute('href');
       const hrefParts = href.split('=');
       const linkValue = hrefParts[hrefParts.length - 1];
       if (linkValue === uriValue) {
          link.classList.add('active-nav');
          let parent = link.parentNode;
          const mon_reqs = ['monr_pr','monr_ar','monr_dr','cms_navs','cms_pages','cms_general','cms_new_page'];
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
<!-- Stylesheets and Scripts -->
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title','Admin Settings')