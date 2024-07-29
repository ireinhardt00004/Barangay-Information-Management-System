@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">Content Management System | <span style="font-size:22px;">Navs</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgba(255,255,255,0.4);">
                <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
                <a class="mx-2 btn btn-sm btn-outline-primary rounded-pill" onclick="create_nav(pages_options);"><i class="fa-solid fa-link"></i> New Nav</a>
            </div>
            <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
        </div>
    </main>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Define pages_options from Blade template
    var pages_options = @json($pages_options);

    // Ensure tableData is correctly passed and formatted
    var tableData = @json($navs_data);
    
    var table = new Tabulator("#table-container", {
        data: tableData,
        placeholder: 'Empty Data',
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 4,
        height: "100%",
        rowFormatter: function(row) {
            row.getElement().style.height = "60px";
        },
        columns: [
            { title: "Nav Name", field: "nav" },
            { title: "Linked Page", field: "page" },
            {
                title: "Action",
                field: "Action",
                formatter: function(cell, formatterParams, onRendered) {
                    var id = cell.getData().id;
                    var nav = cell.getData().nav;
                    var page = cell.getData().page;
                    return `
                        <div id="${id}" class="d-flex justify-content-center">
                            <button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_l(${id});">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            
                        </div>
                    `;
                },
                width: 150
            }
        ]
    });

    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("nav", "like", searchValue);
    }

    function delete_l(id) {
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
                fetch(`{{ url('/navs/delete/${id}') }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'The navigation has been deleted.', 'success');
                        table.updateData(table.getData().filter(item => item.id !== id));
                        window.location.reload();
                    } else {
                        Swal.fire('Error!', 'There was an issue deleting the navigation.', 'error');
                    }
                })
                .catch(error => Swal.fire('Error!', 'There was an issue deleting the navigation.', 'error'));
            }
        });
    }

    function edit_nav(nav, page, id) {
        Swal.fire({
            title: 'Edit Navigation',
            html: `
                <input id="edit-nav-name" class="swal2-input" value="${nav}">
                <input id="edit-nav-page" class="swal2-input" value="${page}">
            `,
            focusConfirm: false,
            preConfirm: () => {
                const navName = Swal.getPopup().querySelector('#edit-nav-name').value;
                const navPage = Swal.getPopup().querySelector('#edit-nav-page').value;
                if (!navName || !navPage) {
                    Swal.showValidationMessage(`Please enter navigation name and linked page`);
                }
                return { navName: navName, navPage: navPage };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { navName, navPage } = result.value;
                // Implement editing logic here
                console.log(`Edit function triggered for id ${id} with new nav name: ${navName} and page: ${navPage}`);
                // Example: window.location.href = `/navs/edit/${id}?nav=${navName}&page=${navPage}`;
            }
        });
    }
</script>

<!-- External Stylesheets and Scripts -->
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>

@section('title','Navs Link - CMS')
@endsection
