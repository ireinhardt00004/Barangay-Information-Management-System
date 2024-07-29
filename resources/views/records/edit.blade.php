@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4"><a href="{{ route('records.index') }}" style="text-decoration:none; color:black;">Records</a> | <span style="font-size:22px;">Edit Record</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <button class="btn btn-primary mb-4" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i> Go Back</button>
            <div class="bg-white rounded shadow p-3 h-100">
                <h5 class="text-center mb-3 mt-2">RECORD OF BARANGAY INHABITANTS BY HOUSEHOLD</h5>
                <form id="editRecordForm" method="POST" action="{{ route('records.update', ['record' => $data['id']]) }}">
                    @csrf
                    @method('PUT')
                    <div class="row d-flex justify-content-center">
                        <div class="mt-1 col-md-10">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">ADDRESS</label>
                                <input class="form-control" type="text" name="address" value="{{ $data['address'] }}" required>
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">CELLPHONE</label>
                                <input class="form-control" type="text" name="cellphone" value="{{ $data['cellphone'] }}" required>
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">HOUSEHOLD NUMBER</label>
                                <input class="form-control" type="text" name="householdNumber" value="{{ $data['householdNumber'] }}" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3 p-1 row d-flex justify-content-center">
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">URI NG PAMAMAHAY</label>
                                <select name="housingType" class="form-select" required>
                                    <option value="1" {{ $data['housingType'] == '1' ? 'selected' : '' }}>MAY ARI</option>
                                    <option value="2" {{ $data['housingType'] == '2' ? 'selected' : '' }}>SQUAT</option>
                                    <option value="3" {{ $data['housingType'] == '3' ? 'selected' : '' }}>NANGUNGUPAHAN</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">MGA URI NG PAMAMAHAY</label>
                                <select name="housingType2" class="form-select" required>
                                    <option value="1" {{ $data['housingType2'] == '1' ? 'selected' : '' }}>Yari sa Semento/ Concrete</option>
                                    <option value="2" {{ $data['housingType2'] == '2' ? 'selected' : '' }}>Yari sa Semento at Kahoy /Semi-Concrete</option>
                                    <option value="3" {{ $data['housingType2'] == '3' ? 'selected' : '' }}>Yari sa Kahoy o Magagaan na Materyales</option>
                                    <option value="4" {{ $data['housingType2'] == '4' ? 'selected' : '' }}>Yari sa Karton, Papel o Plastik/ Salvaged house</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">KURYENTE</label>
                                <select name="kuryente" class="form-select" required>
                                    <option value="1" {{ $data['kuryente'] == '1' ? 'selected' : '' }}>May kuryente</option>
                                    <option value="2" {{ $data['kuryente'] == '2' ? 'selected' : '' }}>Walang kuryente</option>
                                    <option value="3" {{ $data['kuryente'] == '3' ? 'selected' : '' }}>Nakikikabit</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">TUBIG</label>
                                <select name="tubig" class="form-select" required>
                                    <option value="1" {{ $data['tubig'] == '1' ? 'selected' : '' }}>GRIPO(TANZA WATER DISTRICT, SUBD.WATER PROVIDER)</option>
                                    <option value="2" {{ $data['tubig'] == '2' ? 'selected' : '' }}>POSO</option>
                                    <option value="3" {{ $data['tubig'] == '3' ? 'selected' : '' }}>GRIPO DE KURYENTE/SARILING TANGKE</option>
                                    <option value="4" {{ $data['tubig'] == '4' ? 'selected' : '' }}>BALON</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">PALIKURAN</label>
                                <select name="palikuran" class="form-select" required>
                                    <option disabled selected>-- Please select --</option>
                                    <option value="1" {{ $data['palikuran'] == '1' ? 'selected' : '' }}>Inidoro (Water Sealed)</option>
                                    <option value="2" {{ $data['palikuran'] == '2' ? 'selected' : '' }}>Balon (Antipolo type)</option>
                                    <option value="3" {{ $data['palikuran'] == '3' ? 'selected' : '' }}>Walang Palikurang (No Latrine)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">&nbsp;</div>
                    </div>
                    <hr>
                    <div class="mt-3 text-left bg-white h-100">
                        <button type="button" onclick="addRow();" class="mb-1 mt-2 btn rounded-pill btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Add row</button>
                        <div id="table-container" class="table-bordered"></div>
                    </div>
                    <input type="hidden" name="table_data" id="table_data">
                    <button type="button" onclick="saveRecord();" class="btn btn-success m-2" style="float: right;"><i class="fas fa-save"></i> Save Changes</button>
                </form>
                <br><br>
            </div><br>
        </div>
    </main>
</div>
<script>
    let r_id = {{ count($data['table_data']) }};
    let tableData = @json($data['table_data']);

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
            { title: "Sex", field: 'sex', editor: "select", editorParams: { values: ["Male", "Female"] } },
            { title: "Civil Status", field: 'civilStatus', editor: "input" },
            { title: "Occupation", field: 'occupation', editor: "input" },
            { title: "Actions", formatter: "buttonCross", width: 100, align: "center", cellClick: function(e, cell) {
                cell.getRow().delete();
            }}
        ]
    });

    function addRow() {
        table.addRow({ id: r_id });
        r_id++;
    }

    function saveRecord() {
        let tableData = table.getData();
        document.getElementById("table_data").value = JSON.stringify(tableData);
        document.getElementById("editRecordForm").submit();
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
@section('title','Edit Record')
