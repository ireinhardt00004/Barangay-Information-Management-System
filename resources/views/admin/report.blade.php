@extends('layouts.app')

@section('title', 'Reports')
@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">View Reports</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="content-wrap container mb-2">
                <div class="form-wrap p-2">
                    <div class="container">
                        @if($reports->isNotEmpty())
                            <input type="text" id="search-input" placeholder="Search by Full Name" oninput="search_btn()" />
                            <div id="reportsTable"></div>
                        @else
                            <p>No report filed found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var reportsData = @json($reports);

        var table = new Tabulator("#reportsTable", {
            data: reportsData,
            placeholder: 'No data available',
            layout: "fitColumns",
            pagination: "local",
            paginationSize: 10,
            columns: [
                { title: "Full Name", field: "fullname" },
                { title: "Email Address", field: "email" },
                { title: "Contact", field: "contact_num" },
                { title: "Issue", field: "issue", formatter: function(cell) {
                    var value = cell.getValue();
                    return value.length > 65 ? value.substring(0, 65) + "..." : value;
                }},
                { title: "Status", field: "status", formatter: function(cell) {
                    var value = cell.getValue();
                    var color, text;
                    
                    switch(value.toLowerCase()) {
                        case 'resolved':
                            color = 'green';
                            text = 'Resolved';
                            break;
                        case 'pending':
                            color = 'yellow';
                            text = 'Pending';
                            break;
                        default:
                            color = 'gray';
                            text = value.charAt(0).toUpperCase() + value.slice(1);
                            break;
                    }

                    return `<span style="color: ${color}; font-weight: bold;">${text}</span>`;
                }},
                { title: "Date Filed", field: "created_at" },
                { title: "Actions", field: "id", formatter: "html", width: 200, formatter: function(cell) {
                    var id = cell.getValue();
                    return `
                        <a class="btn btn-outline-primary" href="{{ url('report/view/') }}/${id}">View Report</a>
                        <button class="btn btn-outline-danger" onclick="confirmDelete(${id});">Delete</button>
                    `;
                }},
            ],
        });

        function search_btn() {
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("fullname", "like", searchValue);
        }

        window.search_btn = search_btn; // Make search_btn available globally
    });

    function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        preConfirm: () => {
            return fetch(`delete-report/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Optionally reload the page or update the UI
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'There was an issue deleting the report.',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue deleting the report.',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

</script>

<style>
    th {
        top: 0;
        position: sticky;
        backdrop-filter: blur(6px);
        background-color: rgba(200, 200, 200, 0.2);
        box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.9);
    }
    .form-wrap {
        box-shadow: 0px 0px 5px grey;
        border-radius: 10px;
    }
    .table-wrap {
        max-height: 410px;
        overflow: scroll;
    }
    .content-wrap {
        max-width: 1000px;
    }
    table {
        position: relative;
    }
    tbody a {
        font-weight: bold!important;
    }
    .active-nav {
        font-weight: bold;
        color: #007bff; /* Adjust color as needed */
    }
    .search-btn input {
        outline: none;
        border-radius: 5px;
        border: 1px solid rgba(0, 0, 0, 0.4);
    }
</style>
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
<script src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
