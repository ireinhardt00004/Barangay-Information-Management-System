@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Declined Requests</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="text-center" style="background-color:rgb(255,255,255,0.4); padding:10px; border-radius:10px;">
                <h4>Actions-Legend</h4>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>View request</b> = <button class="btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i></button></span>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>DELETE REQUEST</b> = <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></span>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Move to Pending</b> = <button class="btn btn-sm btn-outline-warning"><i class="fa-regular fa-clock"></i></button></span>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Move to Approve</b> = <button class="btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button></span>
            </div>
            <br>
            <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
            </div>
            <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
        </div>
    </main>
</div>

<style>
    .search-btn input{
        outline:none;
        border-radius:5px;
        border:1px solid rgb(0,0,0,0.4);
    }
    .cstm-hover:hover{
        background-color:rgb(0,0,0,0.3);
        border-radius:10px;
    }
    .act-btn{
        font-size:18px;
    }
    
</style>

<script>
    var tableData = {!! $servicesJson !!};

    var table = new Tabulator("#table-container", {
        data: tableData,
        placeholder: 'Empty Data',
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 4,
        height: "100%",
        responsiveLayout: "collapse",
        rowFormatter: function (row) {
            row.getElement().style.height = "60px";
        },
        columns: [
            { title: "Fullname", field: "full_name", minWidth: 250 },
            { title: "Request Type", field: "request_type", minWidth: 150 },
            { title: "Tracking Code", field: "tracking_code", minWidth: 150 },
            { 
                title: "Date Requested", 
                field: "created_at", 
                minWidth: 200,
                formatter:function(cell, formatterParams, onRendered){
                    var date = new Date(cell.getValue());
                    var options = { month: 'long', day: '2-digit', year: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
                    return date.toLocaleString('en-US', options);
                }
            },
            { 
                title: "Status", 
                field: "status", 
                minWidth: 120,
                formatter: function(cell, formatterParams, onRendered){
                    return "<span style='background-color:yellow; font-weight:bold;'>" + cell.getValue().toUpperCase() + "</span>";
                }
            },
            { 
                title: "Action", 
                field: "action", 
                formatter: function(cell, formatterParams, onRendered){
                    var id = cell.getData().id; // Assuming your data includes an id field
                    return `
                        <button class="btn btn-sm btn-outline-primary" onclick="viewRequest(${id})"><i class="fa-regular fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="confirmAction(2, ${id})"><i class="fa-solid fa-trash-can"></i></button>
                        <button class="btn btn-sm btn-outline-warning" onclick="confirmAction(3, ${id})"><i class="fa-regular fa-clock"></i></button>
                        <button class="btn btn-sm btn-outline-success" onclick="confirmAction(4, ${id})"><i class="fa-solid fa-check"></i></button>
                    `;
                },
                minWidth: 200
            }
        ],
    });

    function search_btn(){
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("full_name", "like", searchValue);
    }

    function confirmAction(mod_type, service_id) {
    let actionText = '';
    let actionColor = '';

    switch(mod_type) {
        case 2:
            actionText = 'delete this request';
            actionColor = '#f27474';
            break;
        case 3:
            actionText = 'move this request to pending';
            actionColor = '#f8bb86';
            break;
        case 4:
            actionText = 'approve this request';
            actionColor = '#a5dc86';
            break;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert this! Do you want to ${actionText}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: actionColor,
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, do it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            mod_request(mod_type, service_id);
        }
    });
}

function mod_request(mod_type, service_id) {
    var formData = new FormData();
    formData.append('mtype', mod_type);
    formData.append('s_id', service_id);

    fetch("{{ route('modify.request') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => response.json())
    .then(data => {
        Swal.close(); // Close the loading indicator
        if (data.error) {
            Swal.fire('Error!', data.error, 'error');
        } else {
            let successMessage = '';
            switch(mod_type) {
                case 2:
                    successMessage = 'Request Declined!';
                    break;
                case 3:
                    successMessage = 'Request Moved to Pending!';
                    break;
                case 4:
                    successMessage = 'Request Approved!';
                    break;
            }
            Swal.fire('Success!', successMessage, 'success');
            // Remove the row from the table
            table.deleteRow(service_id);
        }
    }).catch(error => {
        Swal.close(); // Close the loading indicator
        Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    });
}
    function viewRequest(id) {
        window.location.href = "{{ url('/view-requests') }}/" + id;
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

@section('title', 'Declined Requests')
