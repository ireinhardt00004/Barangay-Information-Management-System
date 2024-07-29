@extends('layouts.app')

@section('title', 'Records')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">All Logs</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="container">
                <
                <div class="bg-white rounded shadow p-3 h-100">
                    <h5 class="text-center mb-3 mt-2">RECORD OF BARANGAY INHABITANTS BY HOUSEHOLD</h5>
                    <div class="row d-flex justify-content-center">
                        <div class="mt-1 col-md-10">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">ADDRESS</label>
                                <input class="form-control" type="text" name="address">
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">CELLPHONE</label>
                                <input class="form-control" type="text" name="cellphone">
                            </div>
                        </div>
                        <div class="mt-1 col-md-5">
                            <div class="mx-2 input-group">
                                <label class="input-group-text">HOUSEHOLD NUMBER</label>
                                <input class="form-control" type="text" name="householdNumber">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3 p-1 row d-flex justify-content-center">
                        <div class="col-md-5">
                            <div class="input-group mt-1 mx-1">
                                <label class="input-group-text">URI NG PAMAMAHAY</label>
                                <select name="housingType" class="form-select">
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
                                <select name="housingType2" class="form-select">
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
                                <select name="kuryente" class="form-select">
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
                                <select name="tubig" class="form-select">
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
                                <select name="palikuran" class="form-select">
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
                        <button onclick="addRow();" class="mb-1 mt-2 btn rounded-pill btn-sm btn-outline-primary"><i class="fa-solid fa-plus"></i> Add row</button>
                        <div id="table-container" class="table-bordered"></div>
                    </div>
                    <button onclick="save_record();" class="btn btn-outline-primary mt-5">Save Record</button>
                    <br><br>
                </div><br>      </div>
            
        </div>
    </main>
</div>

@endsection
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
@section('title','All Logs')
@push('scripts')
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
            { title: "Vaccine Brand", field: 'vcne_brand', editor: "input" },
            { title: "Booster Date", field: 'booster_date', editor: "input" },
            { title: "Booster Brand", field: 'booster_brand', editor: "input" },
        ],
    });

    function addRow() {
        r_id += 1;
        table.addData({
            'id': r_id,
            'surname': '',
            'firstname': '',
            'middlename': '',
            'birthday': '',
            'birthplace': '',
            'rspnp': '',
            'tglosy': '',
            'pssp': '',
            'do1d': '',
            'do2d': '',
            'vcne_brand': '',
            'booster_date': '',
            'booster_brand': '',
        });
    }

    function save_record() {
        let address = document.querySelector('input[name="address"]').value;
        let cellphone = document.querySelector('input[name="cellphone"]').value;
        let householdNumber = document.querySelector('input[name="householdNumber"]').value;
        let housingType = document.querySelector('select[name="housingType"]').value;
        let housingType2 = document.querySelector('select[name="housingType2"]').value;
        let kuryente = document.querySelector('select[name="kuryente"]').value;
        let tubig = document.querySelector('select[name="tubig"]').value;
        let palikuran = document.querySelector('select[name="palikuran"]').value;
        let tableData = btoa(JSON.stringify(table.getData()));

        let postData = new FormData();
        postData.append('save_recordz', 'true');
        postData.append('address', address);
        postData.append('cellphone', cellphone);
        postData.append('householdNumber', householdNumber);
        postData.append('housingType', housingType);
        postData.append('housingType2', housingType2);
        postData.append('kuryente', kuryente);
        postData.append('tubig', tubig);
        postData.append('palikuran', palikuran);
        postData.append('tableData', tableData);

        
    }
</script>
@endpush
