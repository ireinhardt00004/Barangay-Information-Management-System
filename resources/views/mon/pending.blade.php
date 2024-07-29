@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Pending Requests</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="text-center" style="background-color:rgb(255,255,255,0.4); padding:10px; border-radius:10px;">
                <h4>Actions-Legend</h4>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>View request</b> = <button class="btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i></button></span>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Move to Decline</b> = <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-x"></i></button></span>
                <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Move to Approved</b> = <button class="btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button></span>
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
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }

    var tableData = @json($servicesJson); 

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
            { title: "Fullname", field: "full_name", minWidth: 250 },
            { title: "Request Type", field: "request_type", minWidth: 150 },
            { title: "Tracking Code", field: "tracking_code", minWidth: 150 },
            { title: "Date Requested", field: "created_at", minWidth: 200, formatter: function(cell, formatterParams, onRendered) {
                return formatDate(cell.getValue());
            }},
            { 
                title: "Status", 
                field: "status", 
                minWidth: 120, 
                formatter: function(cell, formatterParams, onRendered) {
                    let value = cell.getValue();
                    return value === 'Pending' ? `<span style="color: yellow; font-weight: bold;">${value}</span>` : value;
                }
            },
            { 
                title: "Action", 
                field: "action", 
                formatter: function(cell, formatterParams, onRendered) {
                    let data = cell.getRow().getData(); // Get the data for the row
                    let service_id = data.id; // Adjust this to the correct ID field in your data
                    return `
                        <button class="btn btn-sm btn-outline-primary" onclick="viewRequest(${service_id})">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger mx-1" onclick="decline(${service_id})">
                            <i class="fa-solid fa-x"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="confirmAction(3, ${service_id})">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    `;
                }, 
                minWidth: 150 
            }
        ],
    });

    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        console.log("Searching for: " + searchValue); // Debugging line
        table.setFilter("full_name", "like", searchValue);
    }

    function confirmAction(mod_type, service_id) {
    let actionText = '';
    let actionColor = '';

    switch(mod_type) {
        case 2:
            actionText = 'decline this request';
            actionColor = '#f27474';
            break;
        case 3:
            actionText = 'move this request to approved';
            actionColor = '#81c784'; // Color for approve action
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
                onBeforeOpen: () => {
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

    fetch("{{ route('pendingmodify.request') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => response.text())
    .then(text => {
        console.log('Response Text:', text); // Debugging line
        try {
            const data = JSON.parse(text);
            if (data.error) {
                Swal.fire('Error!', data.error, 'error');
            } else {
                switch (mod_type) {
                    case 2:
                        Swal.fire('Success!', 'Request Declined!', 'success');
                        break;
                    case 3:
                        Swal.fire('Success!', 'Request moved to Approved!', 'success');
                        break;
                }
                table.deleteRow(service_id);
            }
        } catch (e) {
            Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
            console.error('Error parsing JSON:', e);
        }
    }).catch(error => {
        Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
        console.error('Error with fetch request:', error);
    });
}


    function viewRequest(id) {
        window.location.href = "{{ url('/view-requests') }}/" + id;
    }

    function decline(service_id) {
    Swal.fire({
        title: 'Comment a reason to decline',
        html: `
        <form id="decline-form">
            <input name="s_id" type="hidden" value="${service_id}">
            <div class="container">
                <textarea class="form-control" placeholder="Your request is declined because..." name="reason" required></textarea>
            </div>
            <div class="text-right mt-3">
                <button class="btn btn-danger" type="submit">Decline</button>
                <button class="btn btn-secondary ml-2" type="button" onclick="Swal.close();">Cancel</button>
            </div>
        </form>
        `,
        showConfirmButton: false,
        showCancelButton: true,
        didOpen: () => {
            const form = document.getElementById('decline-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                submitDeclineForm(service_id);
            });
        }
    });
}

function submitDeclineForm(service_id) {
    var form = document.getElementById('decline-form');
    var formData = new FormData(form);
    formData.append('s_id', service_id);

    // Show loading spinner
    Swal.fire({
        title: 'Declining...',
        text: 'Please wait while we process your request.',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch("{{ route('decline.request') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }).then(response => response.json())
    .then(data => {
        Swal.close();  // Close loading spinner

        if (data.error) {
            Swal.fire('Error!', data.error, 'error');
        } else {
            Swal.fire('Success!', 'Request Declined!', 'success');
            // Remove the row with the given service_id from the table
            table.deleteRow(service_id);
        }
    }).catch(error => {
        Swal.close();  // Close loading spinner

        Swal.fire('Error!', 'An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    });
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
@section('title','Pending Requests')