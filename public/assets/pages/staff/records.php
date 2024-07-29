<?php 

if(isset($_POST['delete_record'])){
    $rid = $_POST['record_id'];
    $sql = $conn->prepare("DELETE FROM `records` WHERE id=:id;");
    $sql->bindParam(":id",$rid);
    $sql->execute();
    act_logger("Deleted a record | record ID: '$rid' ", $current_uri);

    echo "<script>window.location.href='/$c_role&v=records';</script>";
}

?>

    <div class=" shadow search-btn p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
        <div class="row">
            <div class="col-auto">
                <input class="form-control mx-2" autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search ..">
            </div>
            <div class="col-auto">
                <a href="/<?=$c_role;?>&v=new_record" class="mt-2 mx-2 btn btn-sm btn-outline-primary rounded-pill"><i class="fa-solid fa-clipboard-user"></i> New Record</a>
            </div>
            <div class="col-auto">
                <a href="/save_to_xlxs.php" class="mt-2 mx-2 btn btn-sm btn-outline-success rounded-pill"><i class="fa-solid fa-file-arrow-down"></i> Save all records as Excel</a>
            </div>
        </div>
    </div>
    <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
    <style>

        .search-btn input{
            outline:none;
            border-radius:5px;
            border:1px solid rgb(0,0,0,0.4);
            /* box-shadow: 0px 1px 3px 1px rgb(0,0,0,0.3); */
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

        var tableData = [
<?php
$records_data = get_all_records();
$last_data = end($records_data);
foreach($records_data as $data){
    $id = $data['id'];
    $address = $data['address'];
    $cellphone = $data['cellphone'];
    $household_number = $data['householdNumber'];
    $date_recorded = $data['date_created'];
    $view_btn = '<a href="/'.$c_role.'&v=view_record&id='.$id.'" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i> View</a>';
    $edit_btn = '<a href="/'.$c_role.'&v=edit_record&id='.$id.'" class="mx-1 btn btn-sm btn-outline-warning"><i class="fa-regular fa-pen-to-square"></i> Edit</a>';
    $delete_btn = '<a onclick="delete_record('.$id.');" class="mx-1 btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></a>';
    $download_btn = '<a href="/save_to_xlxs.php?id='.$id.'" class="mx-1 btn btn-sm btn-outline-success"><i class="fa-solid fa-file-arrow-down"></i></a>';
    $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.$edit_btn.$delete_btn.$download_btn.'</div>';
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "address":"'.$address.'",
                "cellphone":"'.$cellphone.'",
                "household_number":"'.$household_number.'",
                "date_recorded":"'.$date_recorded.'",
                "Action": `'.$btns.'`,
            }'.$endz;
}
?>

        ];

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
                { title: "Address", field: "address" },
                { title: "Cellphone", field: "cellphone" },
                { title: "House Hold Number", field: "household_number" },
                { title: "Date Recorded", field: "date_recorded" },
                { title: "Action", field: "Action", formatter: "html", width: 250 },
            ],
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter([[
                { field: 'address', headerFilter: true, type: 'like', value: searchValue },
                { field: 'cellphone', headerFilter: true, type: 'like', value: searchValue },
                { field: 'household_number', headerFilter: true, type: 'like', value: searchValue },
                { field: 'date_recorded', headerFilter: true, type: 'like', value: searchValue },
            ]]);
        }

    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    