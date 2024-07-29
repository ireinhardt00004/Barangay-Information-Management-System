@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Request Logs</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <p>The <b>staff</b> changed the <b>status</b> of a request from the <b>tracking code</b>: </p>
            <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search email...">
            </div>

            <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
        </div>
    </main>
</div>

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
    .status-pending {
        font-weight: bold;
        background-color: lightyellow;
    }
    .status-approved {
        font-weight: bold;
        background-color: lightgreen;
    }
    .status-declined {
        font-weight: bold;
        background-color: lightcoral;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script>
    var tableData = @json($logs);
    console.log(tableData); // Check the data structure

    var table = new Tabulator("#table-container", {
        data: tableData,
        placeholder: 'Empty Data',
        layout: "fitDataStretch",
        pagination: "local",
        paginationSize: 4,
        height: "100%",
        rowFormatter: function (row) {
            row.getElement().style.height = "60px";
        },
        columns: [
            { title: "User Email", field: "users", maxWidth: 500 }, 
            { title: "Modified By (Staff Email)", field: "modifiedBy", maxWidth: 500 },
            { title: "Tracking Code", field: "tracking_code", maxWidth: 500 },
            { title: "Status", field: "status", formatter: function(cell, formatterParams) {
                var status = cell.getValue();
                var statusClass = '';
                switch (status) {
                    case 'approved':
                        statusClass = 'status-approved';
                        break;
                    case 'declined':
                        statusClass = 'status-declined';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        break;
                }
                cell.getElement().classList.add(statusClass);
                return status.charAt(0).toUpperCase() + status.slice(1); // Capitalize the first letter
            }},
            { title: "Date Modified", field: "updated_at" },
        ],
        initialSort: [
            { column: "updated_at", dir: "desc" } // Sort by date modified
        ],
    });

    // Function to filter table based on search input
    function search_btn() {
        var searchValue = document.getElementById("search-input").value.toLowerCase();
        table.setFilter(function(data) {
            var userEmail = data["users"].toLowerCase();
            var modifiedByEmail = data["modifiedBy"].toLowerCase();
            return userEmail.indexOf(searchValue) > -1 || modifiedByEmail.indexOf(searchValue) > -1;
        });
    }

    // Function to handle request modification
    function mod_request(mod_type, service_id) {
        var formData = new FormData();
        formData.append('mtype', mod_type);
        formData.append('s_id', service_id);

        fetch("/staff", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (!response.ok) {
                toast('Error! ', '2000', 'rgb(255,0,0,0.5)');
            } else {
                switch (mod_type) {
                    case 2:
                        toast('Request Declined! ', '1500', 'rgb(255,0,0,0.5)');
                        break;
                    case 3:
                        toast('Request moved to Pending! ', '1500', 'rgb(255,255,0,0.5)');
                        break;
                    case 4:
                        toast('Request Approved! ', '1500', 'rgb(0,255,0,0.5)');
                        break;
                }
                table.deleteRow(service_id); // Assuming service_id is a row ID in Tabulator
            }
            return response.text();
        }).then(data => {
            console.log(data);
        }).catch(error => {
            console.error('Error:', error);
        });
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
@section('title','Request Logs')
