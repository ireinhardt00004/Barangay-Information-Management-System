@extends('layouts.app')

@section('content')
@include('layouts.navs')
<div class="semi-body d-flex align-items-center">
    <div class="container w-100">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-10">
                <h2>Track Your Request</h2>
                <div class="input-group shadow">
                    <input onkeydown="if(event.keyCode === 13){search();}" class="form-control shadow-none" placeholder="Enter tracking code" name="tracking_code" id="codebox" type="search">
                    <button class="btn btn-primary" onclick="search();">
                    <i class="fas fa-search"></i> Search</button>
                </div>
                <div>
                    <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function search(){
    const code = document.getElementById("codebox").value;
    window.location.href = '{{ route("track_request") }}?c=' + code;
}

document.addEventListener("DOMContentLoaded", function() {
    @if(isset($data))
        var tableData = [
            {
                "name": "{{ $data->users->fname }} {{ $data->users->middlename }} {{ $data->users->lname }}",
                "request_type": "{{ $data->request_type }}",
                "date_requested": "{{ \Carbon\Carbon::parse($data->created_at)->format('F d, Y h:i A') }}",
                "status": "{{ ucfirst($data->status) }}",
            }
        ];

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
                { title: "Fullname", field: "name" },
                { title: "Request Type", field: "request_type" },
                { title: "Date Requested", field: "date_requested" },
                { title: "Status", field: "status", formatter: function(cell, formatterParams, onRendered) {
                    var value = cell.getValue().toLowerCase();
                    var color = '';

                    switch(value) {
                        case 'approved':
                            color = 'green';
                            break;
                        case 'declined':
                            color = 'red';
                            break;
                        case 'pending':
                            color = 'yellow';
                            break;
                        default:
                            color = 'black';
                    }

                    cell.getElement().style.color = color;
                    return cell.getValue();
                }},
            ],
        });
    @else
        Swal.fire({
            icon: 'info',
            title: 'No Results!',
            text: 'No request found for the given tracking code.',
        });
    @endif
});
</script>

<style>
:root {
    --cover-op: 0.8;
}
body {
    background-color: rgba(238, 225, 180, 0.9);
}
nav {
    background-color: rgb(0, 0, 0, 0.8);
}
.semi-body {
    height: 100vh;
}
.card {
    user-select: none;
    transition: .6s;
    color: white;
    overflow: hidden;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid white;
    box-shadow: 0px 3px 2px 1px rgb(0, 0, 0, 0.1);
    background: linear-gradient( rgba(0, 0, 0, var(--cover-op)), rgba(0, 0, 0, var(--cover-op)) ), url('{{ asset('assets/imgs/logo.png') }}');
    background-size: cover;
    background-position: center;
}
.card:hover {
    color: white;
    transition: .3s;
    transform: translateY(-15px);
    box-shadow: 0px 5px 10px 2px rgb(0, 0, 0, 0.5);
}
.card:active {
    transition: .1s;
    color: rgba(239, 227, 187, 1);
    transform: scale(1.1);
    box-shadow: none;
}
</style>

<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title','Track Request')
