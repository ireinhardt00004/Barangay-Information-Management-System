@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Records</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="shadow search-btn p-2 rounded" style="background-color:rgba(255,255,255,0.4);">
                <div class="row">
                    <div class="col-auto">
                        <input class="form-control mx-2" autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search ..">
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('records.create') }}" class="mt-2 mx-2 btn btn-sm btn-primary rounded-pill">
                            <i class="fa-solid fa-clipboard-user"></i> New Record
                        </a>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('records.export')}}" class="mt-2 mx-2 btn btn-sm btn-success rounded-pill">
                            <i class="fa-solid fa-file-arrow-down"></i> Save all records as Excel
                        </a>
                    </div>
                </div>
            </div>
            <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>        
        </div>
    </main>
</div>

<style>
    .search-btn input {
        outline: none;
        border-radius: 5px;
        border: 1px solid rgba(0,0,0,0.4);
    }
    .cstm-hover:hover {
        background-color: rgba(0,0,0,0.3);
        border-radius: 10px;
    }
    .act-btn {
        font-size: 18px;
    }
</style>

<script>
    var tableData = @json($recordsData);

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
            { title: "Address", field: "address" },
            { title: "Cellphone", field: "cellphone" },
            { title: "House Hold Number", field: "household_number" },
            { title: "Date Recorded", field: "date_recorded" },
            { title: "Action", field: "Action", formatter: "html", width: 250 },
        ],
    });

    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter([
            { field: 'address', type: 'like', value: searchValue },
            { field: 'cellphone', type: 'like', value: searchValue },
            { field: 'household_number', type: 'like', value: searchValue },
            { field: 'date_recorded', type: 'like', value: searchValue },
        ]);
    }

    function deleteRecord(recordId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/records/delete/${recordId}`, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                method: 'DELETE'
            }).then((response) => {
                if (response.ok) {
                    Swal.fire(
                        'Deleted!',
                        'Your record has been deleted.',
                        'success'
                    ).then(() => {
                        window.location.reload(); // Reload the page after successful deletion
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'There was an issue deleting the record.',
                        'error'
                    );
                }
            }).catch((error) => {
                Swal.fire(
                    'Error!',
                    'There was an issue deleting the record.',
                    'error'
                );
            });
        }
    });
}
</script>
<script>
    function exportToExcel(recordId) {
        const url = `{{ url('/saved_to_xlxs') }}/${recordId}`;
        window.location.href = url;
    }
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
@section('title','Household Records List')
