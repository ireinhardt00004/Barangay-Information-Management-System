@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Resident List</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="shadow search-btn d-flex p-2">
                <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
            </div>
            <div id="table-container" style="font-size:16px;"></div>
        </div>
    </main>
</div>

<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />

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
        }
    });
</script>

<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">

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
            { title: "Action", field: "Action", formatter: "html", colspan:2, width:100 },
        ],
    });

    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("Firstname", "like", searchValue);
    }
</script>

<script>
    function delete_resident(id) {
        const csrfToken = "{{ csrf_token() }}";
    
        Swal.fire({
            title: 'Do you confirm to delete this account?',
            html: `
            <form id="deleteUserForm" class="needs-validation" method="post">
                <input type="hidden" name="_token" value="${csrfToken}">
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
    
        fetch("{{ route('delete.resident') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
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

<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/js/tabulator.min.js"></script>
@endsection
@section('title','List of Residents Table')
