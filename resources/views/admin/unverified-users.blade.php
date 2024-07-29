@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Unverified Users</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center" style="background-color:rgb(255,255,255,0.4); padding:10px; border-radius:10px;">
                        <h4>Actions-Legend</h4>
                        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>View ID</b> = <button class="btn btn-sm btn-outline-primary" onclick="view_id('sample.jpg')"><i class="fa-regular fa-eye"></i></button></span>
                        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Decline</b> = <button class="btn btn-sm btn-outline-danger" onclick="mod_request(2, 1)"><i class="fa-solid fa-x"></i></button></span>
                        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Make Verified</b> = <button class="btn btn-sm btn-outline-success" onclick="mod_request(1, 1)"><i class="fa-solid fa-check"></i></button></span>
                    </div>
                </div>
                <br>
                <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                    <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
                </div>
                <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
            </div>
        </div>
    </main>
</div>

<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<style>
    .search-btn input {
        outline: none;
        border-radius: 5px;
        border: 1px solid rgb(0,0,0,0.4);
    }
    .cstm-hover:hover {
        background-color: rgb(0,0,0,0.3);
        border-radius: 10px;
    }
    .act-btn {
        font-size: 18px;
    }
</style>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Data Preparation
    var tableData = @json($users);

    // Initialize Tabulator
    var table = new Tabulator("#table-container", {
        data: tableData,
        placeholder: 'Empty Data',
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 4,
        height: "100%",
        rowFormatter: function (row) {
            row.getElement().style.height = "60px";
        },
        columns: [
            { title: "Fullname", field: "fullname", minWidth: 250 },
            { title: "Email", field: "email", minWidth: 120 },
            { title: "Action", field: "actions", formatter: "html", minWidth: 200 },
        ],
    });

    // Search Function
    window.search_btn = function() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("fullname", "like", searchValue);
    };
    
    window.mod_request = function(mod_type, user_id) {
    let actionText = mod_type === 2 ? 'Decline' : 'Make Verified';
    let confirmText = mod_type === 2 ? 'Are you sure you want to decline this request?' : 'Are you sure you want to verify this resident account?';
    let url = mod_type === 2 ? `{{ url('/admin/verify-user/decline/') }}/${user_id}` : `{{ url('/admin/verify-user/approve/') }}/${user_id}`;
    
    Swal.fire({
        title: 'Confirmation',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: mod_type === 2 ? '#dc3545' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: actionText,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                if (!response.ok) {
                    Swal.fire('Error!', 'There was an error processing your request.', 'error');
                    return response.text();
                }
                Swal.fire({
                    title: mod_type === 2 ? 'Request Declined!' : 'Request Approved!',
                    icon: mod_type === 2 ? 'error' : 'success',
                    text: mod_type === 2 ? 'The request has been declined.' : 'The request has been approved.'
                }).then(() => {
                    window.location.reload(); 
                });
                return response.text();
            }).then(data => {
                console.log('Response data:', data);
            }).catch(error => {
                console.log('Fetch error:', error);
                Swal.fire('Error!', 'There was an error processing your request.', 'error');
            });
        }
    });
};
    // View ID Function
    window.view_id = function(img) {
        Swal.fire({
            title: 'Valid ID',
            html: `
                <form method="post" enctype="multipart/form-data">
                    <div class="container">
                        <div>
                            <img class="img-fluid mb-2 rounded" style="border:2px solid grey!important;" src="resident_valid_id/${img}" id="preview-img-profile" draggable="false">
                        </div>
                    </div>
                    <input class="btn btn-secondary m-3" type="button" onclick="Swal.close();" value="Okay">
                </form>
            `,
            showConfirmButton: false,
        });
    };
});
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('title', 'Validate Account')
