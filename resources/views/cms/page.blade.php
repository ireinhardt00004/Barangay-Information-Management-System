@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">Content Management System | <span style="font-size:22px;">CMS - Page</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
                <a class="mx-2 btn btn-sm btn-outline-primary rounded-pill" href="{{ route('cmspages.create') }}"><i class="fa-solid fa-file-lines"></i> Create New Page</a>
            </div>
            <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
        </div>
    </main>
</div>

<!-- Stylesheets -->
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">

<!-- Scripts -->
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Highlight active navigation link
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
        }
    });

    // Table data
    var tableData = @json($pages);

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
            { title: "Page Name", field: "page_name" },
            { title: "Contents", field: "contents" },
            {
                title: "Action",
                field: "action",
                formatter: function(cell, formatterParams, onRendered) {
                    var data = cell.getData();
                    var id = data.id;
                    return `
                        <div id="${id}" class="d-flex justify-content-center">
                            <button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="deletePage(${id});">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <a class="mx-1 btn btn-sm btn-outline-warning act-btn" href="/cmspages/edit/${id}">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    `;
                },
                width: 150
            }
        ]
    });

    // Search function
    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("page_name", "like", searchValue);
    }

    function deletePage(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You won't be able to revert this!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/cmspages/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'The page has been deleted.', 'success');
                        table.updateData(table.getData().filter(item => item.id !== id));
                        window.location.reload();
                    } else {
                        Swal.fire('Error!', 'There was an issue deleting the page.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'There was an issue deleting the page.', 'error');
                });
            }
        });
    }
</script>

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
@endsection

@section('title','CMS - Page')
