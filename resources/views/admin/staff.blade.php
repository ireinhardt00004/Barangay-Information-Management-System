@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
       <div class="container-fluid px-4 mb-4">
          <div class="d">
             <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Staff List</span></h1>
          </div>
          <hr style="border:1px solid black;">
         
         <div class="container-fluid px-4">
            <div class="shadow search-btn d-flex p-2">
                <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
                <a class="mx-2 btn btn-sm btn-primary" onclick="create_user('Staff', 1);"><i class="fa-solid fa-user-plus"></i>Add</a>
            </div>
            <div id="table-container" style="font-size:16px;"></div>
        </div>
    </main>
    </div>
   <script>
    function create_user(name, ut) {
        const csrfToken = "{{ csrf_token() }}";
    
        Swal.fire({
            title: 'Create New ' + name,
            html: `
            <form class="needs-validation" action="/post-newstaff" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input class="d-none" name="user_type" value="${ut}">
                <div class="">
                    <div class="d-flex justify-content-center">
                        <div class="w-auto w-100">
                            <input class="form-control" type="text" placeholder="First name" name="fname" value="" autocomplete="off" required>
                        </div>
                        <div class="mx-2 w-100">
                            <input class="form-control" type="text" placeholder="Middle name" name="middlename" value="" autocomplete="off">
                        </div>
                        <div class="w-100">
                            <input class="form-control" type="text" placeholder="Last name" name="lname" value="" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="mt-2 text-left">
                        <input class="form-control" type="email" placeholder="New user email" name="email" value="" autocomplete="off" required>
                    </div>
                    <div class="mt-2 text-left d-flex">
                        <input class="form-control" id="pass" type="text" placeholder="New user password.." name="password" value="" autocomplete="off" required>
                        <button class="btn btn-sm btn-primary mx-1" onclick="rpass('pass');return false;"><i class="fa-solid fa-repeat"></i></button>
                    </div>
                </div>
                <input class="btn btn-outline-success m-3" type="submit" name="user_create" value="Create user">
                <input class="btn btn-secondary m-3" type="button" onclick="Swal.close();" value="Cancel">
            </form>
            `,
            showConfirmButton: false,
        });
    }
    </script>
    <script>
        function delete_staff(ut, id) {
            const csrfToken = "{{ csrf_token() }}";
        
            Swal.fire({
                title: 'Do you confirm to delete this account?',
                html: `
                <form id="deleteUserForm" class="needs-validation" method="post">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="user_type" value="${ut}">
                    <input type="hidden" name="uid" value="${id}">
                    <input class="btn btn-outline-danger m-3" type="button" value="I Confirm" onclick="confirmDelete()">
                    <input class="btn btn-secondary m-3" type="button" onclick="Swal.close();" value="Cancel">
                </form>
                `,
                showConfirmButton: false,
            });
        }
        
        function confirmDelete() {
            const form = document.getElementById('deleteUserForm');
            const formData = new FormData(form);
            const csrfToken = "{{ csrf_token() }}";
        
            fetch("{{ route('delete.staff') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', data.message, 'success');
                    // Optionally, refresh the page or update the table
                    location.reload();
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'An error occurred while deleting the user.', 'error');
                console.error('Error:', error);
            });
        }
        </script>        
 </div>
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
<style>
    .search-btn input {
        outline: none;
        border-radius: 5px;
        border: 1px solid rgba(0, 0, 0, 0.4);
    }
    .cstm-hover:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
    }
    .act-btn {
        font-size: 18px;
    }
</style>

<script>
    var tableData = @json($users);

    var table = new Tabulator("#table-container", {
        data: tableData,
        placeholder: 'Empty Data',
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 10,
        height: "100%",
        columns: [
            { title: "Firstname", field: "Firstname" },
            { title: "Lastname", field: "Lastname" },
            { title: "Email", field: "Email" },
            { title: "Date created", field: "Date created" },
            { title: "Action", field: "Action", formatter: "html", colspan: 2, width: 100 },
        ],
    });

    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("Firstname", "like", searchValue);
    }
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
@section('title','List of Staff Table')