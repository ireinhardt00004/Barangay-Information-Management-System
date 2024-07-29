<?php

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
    $id = intval($_GET['id']);
    $data = data_extract($id, 'id', get_all_records());
    if($data == false){
      echo "<script>window.location.href='/<?=$c_role;?>&v=reports';</script>";
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
    $table_count = count(json_decode($table_data));
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
                <input class="form-control" type="text" name="address" value="<?=$address;?>">
            </div>
        </div>
        <div class="mt-1 col-md-5">
            <div class="mx-2 input-group">
                <label class="input-group-text">CELLPHONE</label>
                <input class="form-control" type="text" name="cellphone" value="<?=$cellphone;?>">
            </div>
        </div>
        <div class="mt-1 col-md-5">
            <div class="mx-2 input-group">
                <label class="input-group-text">HOUSE HOLD NUMBER</label>
                <input class="form-control" type="text" name="householdNumber" value="<?=$householdNumber;?>">
            </div>
        </div>
    </div>
    <hr>
    <div class="mt-3 p-1 row d-flex justify-content-center">
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">URI NG PAMAMAHAY</label>
                <select name="housingType" class="form-select">
                    <option <?=($housingType == '1')?'selected':''?> value="1">MAY ARI</option>
                    <option <?=($housingType == '2')?'selected':''?> value="2">SQUAT</option>
                    <option <?=($housingType == '3')?'selected':''?> value="3">NANGUNGUPAHAN</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">MGA URI NG PAMAMAHAY</label>
                <select name="housingType2" class="form-select">
                    <option <?=($housingType2 == '1')?'selected':''?> value="1">Yari sa Semento/ Concrete</option>
                    <option <?=($housingType2 == '2')?'selected':''?> value="2">Yari sa Semento at Kahoy /Semi-Concrete</option>
                    <option <?=($housingType2 == '3')?'selected':''?> value="3">Yari sa Kahoy o Magagaan na Materyales</option>
                    <option <?=($housingType2 == '4')?'selected':''?> value="4">Yari sa Karton, Papel o Plastik/ Salvaged house</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">KURYENTE</label>
                <select name="kuryente" class="form-select">
                    <option <?=($kuryente == '1')?'selected':''?> value="1">May kuryente</option>
                    <option <?=($kuryente == '2')?'selected':''?> value="2">Walang kuryente</option>
                    <option <?=($kuryente == '3')?'selected':''?> value="3">Nakikikabit</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">TUBIG</label>
                <select name="tubig" class="form-select">
                    <option <?=($tubig == '1')?'selected':''?> value="1">GRIPO(TANZA WATER DISTRICT, SUBD.WATER PROVIDER)</option>
                    <option <?=($tubig == '2')?'selected':''?> value="2">POSO</option>
                    <option <?=($tubig == '3')?'selected':''?> value="3">GRIPO DE KURYENTE/SARILING TANGKE</option>
                    <option <?=($tubig == '4')?'selected':''?> value="4">BALON</option>
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group mt-1 mx-1">
                <label class="input-group-text">PALIKURAN</label>
                <select name="palikuran" class="form-select">
                    <option disabled selected>-- Please select --</option>
                    <option <?=($palikuran == '1')?'selected':''?> value="1">Inidoro (Water Sealed)</option>
                    <option <?=($palikuran == '2')?'selected':''?> value="2">Balon (Antipolo type)</option>
                    <option <?=($palikuran == '3')?'selected':''?> value="3">Walang Palikurang (No Latrine)</option>
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
    <button onclick="save_record();" class="btn btn-outline-success mt-5">Save Changes</button>
    <br><br>
</div><br>
<script>
        let r_id = <?=$table_count;?>;
        let tableData = <?=$table_data?>;

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
            r_id+=1;
            table.addData(
                {
                    'id': r_id,
                    'surname':'',
                    'firstname':'',
                    'middlename':'',
                    'birthday':'',
                    'birthplace':'',
                    'rspnp':'',
                    'tglosy':'',
                    'pssp':'',
                    'do1d':'',
                    'do2d':'',
                    'vcne_brand':'',
                    'booster_date':'',
                    'booster_brand':'',
                }
            );
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
            postData.append('edit_recordz', 'true');
            postData.append('id', '<?=$id;?>');
            postData.append('address', address);
            postData.append('cellphone', cellphone);
            postData.append('householdNumber', householdNumber);
            postData.append('housingType', housingType);
            postData.append('housingType2', housingType2);
            postData.append('kuryente', kuryente);
            postData.append('tubig', tubig);
            postData.append('palikuran', palikuran);
            postData.append('tableData', tableData);

            fetch('/<?=$c_role;?>', {
                method: 'POST',
                body: postData,
            })
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                toast('[Saved]: Success!','2000','rgb(0,200,0,0.5)')
                // window.location.href="/<?=$c_role;?>&v=records";
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

        // function save_record(){
        //     console.log(JSON.stringify(table.getData()));
        // }

</script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>