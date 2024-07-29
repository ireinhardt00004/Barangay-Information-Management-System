<?php

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
    $id = intval($_GET['id']);
    $data = data_extract($id, 'id', get_all_records());
    if($data == false){
      echo "<script>window.location.href='/staff&v=reports';</script>";
      exit;
    }
    $id = intval($data['id']);
    $address = $data['address'];
    $cellphone = $data['cellphone'];
    $householdNumber = $data['householdNumber'];
    $housingType = $data['housingType'];
    $housingType2 = $data['housingType2'];
    $kuryente = $data['kuryente'];
    $tubig = $data['tubig'];
    $palikuran = $data['palikuran'];
    $td = $data['table_data'];
    $table_data = ($td == 'null')?'[]':base64_decode($td);

    $date_created = $data['date_created'];
}

?>
<button class="btn btn-outline-warning mb-2" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i>Go Back</button>
<div class="bg-white rounded shadow p-3 h-100">
    <h5 class="text-center mb-3 mt-2">RECORD OF BARANGAY INHABITANTS BY HOUSEHOLD</h5>
    <div class="row d-flex justify-content-center">
        <div class="mt-1 col-md-10">
            <div class="mx-2 input-group">
                <label class="input-group-text">ADDRESS</label>
                <input class="form-control bg-white" type="text" value="<?=$address;?>" readonly>
            </div>
        </div>
        <div class="mt-1 col-md-5">
            <div class="mx-2 input-group">
                <label class="input-group-text">CELLPHONE</label>
                <input class="form-control bg-white" type="text" value="<?=$cellphone;?>" readonly>
            </div>
        </div>
        <div class="mt-1 col-md-5">
            <div class="mx-2 input-group">
                <label class="input-group-text ">HOUSE HOLD NUMBER</label>
                <input class="form-control bg-white" type="text" value="<?=$householdNumber;?>" readonly>
            </div>
        </div>
    </div>
    <hr>
    <div class="mt-3 p-1 row d-flex justify-content-center">
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">URI NG PAMAMAHAY</label>
                <select name="housingType" disabled class="bg-white form-select">
                <?php
                if($housingType == '1'){
                    echo '<option selected value="1">MAY ARI</option>';
                }elseif($housingType == '2'){
                    echo '<option selected value="2">SQUAT</option>';
                }elseif($housingType){
                    echo '<option selected value="3">NANGUNGUPAHAN</option>';
                }
                ?>
                    
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">MGA URI NG PAMAMAHAY</label>
                <select name="housingType2" readonly class="bg-white form-select">
                <?php
                if($housingType == '1'){
                    echo '<option selected value="1">Yari sa Semento/ Concrete</option>';
                }elseif($housingType == '2'){
                    echo '<option selected value="2">Yari sa Semento at Kahoy /Semi-Concrete</option>';
                }elseif($housingType == '3'){
                    echo '<option selected value="3">Yari sa Kahoy o Magagaan na Materyales</option>';
                }elseif($housingType == '4'){
                    echo '<option selected value="4">Yari sa Karton, Papel o Plastik/ Salvaged house</option>';
                }
                ?>

                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">KURYENTE</label>
                <select name="kuryente" class="form-select">
                <?php
                if($kuryente == '1'){
                    echo '<option value="1">May kuryente</option>';
                }elseif($kuryente == '2'){
                    echo '<option value="2">Walang kuryente</option>';
                }elseif($kuryente == '3'){
                    echo '<option value="3">Nakikikabit</option>';
                }
                ?>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">TUBIG</label>
                <select name="tubig" class="form-select">
                <?php
                if($tubig == '1'){
                    echo '<option value="1">GRIPO(TANZA WATER DISTRICT, SUBD.WATER PROVIDER)</option>';
                }elseif($tubig == '2'){
                    echo '<option value="2">POSO</option>';
                }elseif($tubig == '3'){
                    echo '<option value="3">GRIPO DE KURYENTE/SARILING TANGKE</option>';
                }elseif($tubig == '4'){
                    echo '<option value="4">BALON</option>';
                }
                ?>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">PALIKURAN</label>
                <select name="palikuran" class="form-select">
                <?php
                if($tubig == '1'){
                    echo '<option value="1">Inidoro (Water Sealed)</option>';
                }elseif($tubig == '2'){
                    echo '<option value="2">Balon (Antipolo type)</option>';
                }elseif($tubig == '3'){
                    echo '<option value="3">Walang Palikurang (No Latrine)</option>';
                }
                ?>
                </select>
            </div>
        </div>
        <div class="mt-1 col-md-5">&nbsp;</div>
    </div>
    <hr>
    <div class="mt-3 text-left bg-white h-100">
        <div id="table-container" class="table-bordered"></div>
    </div>
    <br><br>
</div><br>
<script>
        let r_id = 0;
        let tableData = <?=$table_data;?>;

        let table = new Tabulator("#table-container", {
            data: tableData,
            layout: "fitData",
            height: "100%",
            pagination: "local",
            paginationSize: 5,
            columns: [
                { title: "Surname", field: 'surname', },
                { title: "Firstname", field: 'firstname', },
                { title: "Middle Name", field: 'middlename', },
                { title: "Birthday", field: 'birthday', },
                { title: "Birthplace", field: 'birthplace', },
                { title: "Relasyon sa pinuno ng pamila", field: 'rspnp', },
                { title: "Trabaho/Grade level/Out of school youth", field: 'tglosy', },
                { title: "PWD/Senior/Solo parent", field: 'pssp', },
                { title: "Date of 1st Dose", field: 'do1d', },
                { title: "Date of 2nd Dose", field: 'do2d', },
                { title: "Vaccine Brand", field: 'vcne_brand', },
                { title: "Booster Date", field: 'booster_date', },
                { title: "Booster Brand", field: 'booster_brand', },
            ],
        });

</script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>