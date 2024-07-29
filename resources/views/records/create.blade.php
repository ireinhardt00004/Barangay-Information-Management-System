@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4"><a href="{{route('records.index')}}" style="text-decoration:none; color:black;">Records </a> | <span style="font-size:22px;">New Record</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="bg-white rounded shadow p-3 h-100">
                <h5 class="text-center mb-3 mt-2">RECORD OF BARANGAY INHABITANTS BY HOUSEHOLD</h5>
                <form id="recordForm" action="{{ route('records.store') }}" method="POST">
                    @csrf
                    <div class="row d-flex justify-content-center">
                        <div class="mt-1 col-md-10">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">ADDRESS</label>
                                <input class="form-control" type="text" name="address" required>
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">CELLPHONE</label>
                                <input class="form-control" type="text" name="cellphone" required>
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">HOUSE HOLD NUMBER</label>
                                <input class="form-control" type="text" name="householdNumber" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3 p-1 row d-flex justify-content-center">
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">URI NG PAMAMAHAY</label>
                                <select name="housingType" class="form-select" required>
                                    <option disabled selected>-- Please select --</option>
                                    <option value="1">MAY ARI</option>
                                    <option value="2">SQUAT</option>
                                    <option value="3">NANGUNGUPAHAN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">MGA URI NG PAMAMAHAY</label>
                                <select name="housingType2" class="form-select" required>
                                    <option disabled selected>-- Please select --</option>
                                    <option value="1">Yari sa Semento/ Concrete</option>
                                    <option value="2">Yari sa Semento at Kahoy /Semi-Concrete</option>
                                    <option value="3">Yari sa Kahoy o Magagaan na Materyales</option>
                                    <option value="4">Yari sa Karton, Papel o Plastik/ Salvaged house</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">KURYENTE</label>
                                <select name="kuryente" class="form-select" required>
                                    <option disabled selected>-- Please select --</option>
                                    <option value="1">May kuryente</option>
                                    <option value="2">Walang kuryente</option>
                                    <option value="3">Nakikikabit</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">TUBIG</label>
                                <select name="tubig" class="form-select" required>
                                    <option disabled selected>-- Please select --</option>
                                    <option value="1">GRIPO(TANZA WATER DISTRICT, SUBD.WATER PROVIDER)</option>
                                    <option value="2">POSO</option>
                                    <option value="3">GRIPO DE KURYENTE/SARILING TANGKE</option>
                                    <option value="4">BALON</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">PALIKURAN</label>
                                <select name="palikuran" class="form-select" required>
                                    <option disabled selected>-- Please select --</option>
                                    <option value="1">Inidoro (Water Sealed)</option>
                                    <option value="2">Balon (Antipolo type)</option>
                                    <option value="3">Walang Palikurang (No Latrine)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">&nbsp;</div>
                    </div>
                    <hr>
                    <div class="mt-3 text-left bg-white h-100">
                        <button type="button" onclick="addRow();" class="mb-1 mt-2 btn rounded-pill btn-sm btn-outline-primary">
                            <i class="fa-solid fa-plus"></i> Add row
                        </button>
                        <div id="table-container" class="table-bordered"></div>
                    </div>
                    <input type="hidden" id="table_data" name="table_data">
                    <button type="button" onclick="save_record();" class="btn btn-primary m-2" style="float:right;"><i class="fas fa-save"></i> Save Record</button>
                </form>
                <br><br>
            </div><br>
        </div>
    </main>
</div>

<script>
    let r_id = 0;
    let tableData = [];

    let table = new Tabulator("#table-container", {
        data: tableData,
        layout: "fitData",
        height: "100%",
        pagination: "local",
        paginationSize: 5,
        columns: [
            { title: "row no.", field: 'id' },
            { title: "Surname", field: 'surname', editor: "input" },
            { title: "Firstname", field: 'firstname', editor: "input" },
            { title: "Middle Name", field: 'middlename', editor: "input" },
            { title: "Birthday", field: 'birthday', editor: "input" },
            { title: "Birthplace", field: 'birthplace', editor: "input" },
            { title: "Relasyon sa pinuno ng pamila", field: 'rspnp', editor: "input" },
            { title: "Trabaho/Grade level/Out of school youth", field: 'tglosy', editor: "input" },
            { title: "PWD/Senior/Solo parent", field: 'pssp', editor: "input" },
            { title: "Date of 1st Dose", field: 'do1d', editor: "input" },
            { title: "Date of 2nd Dose", field: 'do2d', editor: "input" },
            { title: "Vaccine Brand", field: 'vcne', editor: "input" },
            { title: "Actions", formatter: actionButtons }
        ],
    });

    function actionButtons(cell, formatterParams, onRendered) {
        return "<button type='button' onclick='deleteRow(" + cell.getRow().getIndex() + ")' class='btn btn-outline-danger btn-sm'><i class='fa-solid fa-trash'></i> Remove</button>";
    }

    function addRow() {
        table.addRow({ id: r_id + 1 });
        r_id++;
    }

    function deleteRow(index) {
        table.deleteRow(index);
    }

    function save_record() {
    let data = table.getData();
    console.log("Table Data before submission:", data); 
    let base64EncodedData = btoa(JSON.stringify(data)); // Base64 encode the JSON data
    document.getElementById("table_data").value = base64EncodedData;
    document.getElementById("recordForm").submit();
    }


    @if(session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}'
        });
    @endif
</script>

<!-- Stylesheets and Scripts -->
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title','Create New Record')
