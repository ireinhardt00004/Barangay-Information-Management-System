<?php 

$pages_options = '';
foreach(get_all_pages() as $pdata){
    $pages_options.='<option value="'.$pdata['id'].'">'.base64_decode($pdata['page_name']).'</option>';
}
$pages_options = base64_encode($pages_options);
?>
    <div class="row">
        <div class="col-md-12 text-center" style="background-color:rgb(255,255,255,0.4); padding:10px; border-radius:10px;">
            <h4>Actions-Legend</h4>
            <!-- 1 -->
            <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>View ID</b> = <button class="btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i></button></span>
            <!-- 2 -->
            <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Decline</b> = <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-x"></i></button></span>
            <!-- 3 -->
            <!-- 4 -->
            <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Make Verified</b> = <button class="btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button></span>
        </div>
    </div>
    <br>
    <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
        <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
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
$member_data = get_all_data(3);
$last_data = end($member_data);
foreach($member_data as $data){
    $verify = $data['verified'];
    $v_id = $data['valid_id'];

    if($verify === "true" || $v_id === "none"){
        continue;
    }

    $id = $data['id'];
    $muuid = $data['uid'];
    $fullname = $data["firstname"].' '.$data["middlename"].' '.$data["lastname"];
    $email = $data['email'];
    $valid_id = $data["valid_id"];

    $decline_btn = '<button onclick="mod_request(2,\''.$muuid.'\')" class="mx-1 btn btn-sm btn-outline-danger"><i class="fa-solid fa-x"></i></button>';
    $approve_btn = '<button onclick="mod_request(3,\''.$muuid.'\')" class="mx-1 btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button>';
    $view_btn = '<button onclick="view_id(\''.$valid_id.'\');" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-solid fa-eye"></i></button>';
    $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.$decline_btn.$approve_btn.'</div>';
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "id":"'.$muuid.'",
                "fullname":"'.$fullname.'",
                "email":"'.$email.'",
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
                { title: "Fullname", field: "fullname" , minWidth: 250},
                { title: "Email", field: "email", minWidth: 120 },
                { title: "Action", field: "Action", formatter: "html", minWidth: 200 },
            ],
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("fullname", "like", searchValue);
        }


        function mod_request(mod_type, uuid){
            var formData = new FormData();
            formData.append('mtype2', mod_type);
            formData.append('uuid', uuid);

            fetch("/<?=$c_role;?>",{
                method: 'POST',
                body: formData
            }).then(response=>{
                if(!response.ok){
                    toast('Error! ','2000','rgb(255,0,0,0.5)');
                }else{
                    switch(mod_type){
                        case 2:
                            toast('Request Declined! ','1500','rgb(255,0,0,0.5)');
                            break;
                        case 3:
                            toast('Request Approved! ','1500','rgb(0,255,0,0.5)');
                            break;
                    }
                    table.deleteRow(uuid);
                }
                return response.text();
            }).then(data=>{
                console.log(data);
            })
        }

function view_id(img){
    Swal.fire({
        title: 'Valid ID',
        html: `
        <form class="" method="post" enctype="multipart/form-data">
            <div class="container">
                <div>
                    <img class="img-fluid mb-2 rounded" style="border:2px solid grey!important;" src="assets/valid_ids/`+img+`" id="preview-img-profile" draggable="false">
                </div>

            </div>
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Okay">
        </form>
        `,
        showConfirmButton: false,
    });
}

    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    