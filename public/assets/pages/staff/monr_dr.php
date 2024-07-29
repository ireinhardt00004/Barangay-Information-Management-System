<?php 

if(isset($_POST['delete_request'])){
    $rid = $_POST['request_id'];
    $sql = $conn->prepare("DELETE FROM `services` WHERE id=:id;");
    $sql->bindParam(":id",$rid);
    $sql->execute();
}

$pages_options = '';
foreach(get_all_pages() as $pdata){
    $pages_options.='<option value="'.$pdata['id'].'">'.base64_decode($pdata['page_name']).'</option>';
}
$pages_options = base64_encode($pages_options);
?>
    <div class="text-center" style="background-color:rgb(255,255,255,0.4); padding:10px; border-radius:10px;">
        <h4>Actions-Legend</h4>
        <!-- 1 -->
        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>View request</b> = <button class="btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i></button></span>
        <!-- 2 -->
        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>DELETE REQUEST</b> = <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></button></span>
        <!-- 3 -->
        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Move to Pending</b> = <button class="btn btn-sm btn-outline-warning"><i class="fa-regular fa-clock"></i></button></span>
        <!-- 4 -->
        <span style="background-color:rgb(0,0,0,0.1);" class="btn mx-3 mt-1"><b>Move to Approve</b> = <button class="btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button></span>

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
$navs_data = get_all_services();
$last_data = end($navs_data);
foreach($navs_data as $data){
    $status = trim($data['status']);
    if(empty($status) || $status != 'declined'){
        continue;
    }
    $id = $data['id'];
    $user_data = data_extract($data['user_id'],'uid', get_all_data(2));
    $firstn = $user_data['firstname'];
    $middlen = $user_data['middlename'];
    $lastn = $user_data['lastname'];
    $fullname = ucfirst($firstn).' '.ucfirst($middlen).' '.ucfirst($lastn);

    $req_type = data_extract(intval($data['request_type']), 'id', get_all_rtypes())['request_type'];
    $date_req = $data['date_created'];
    $pending_btn = '<button onclick="mod_request(3,\''.$id.'\')" class="mx-1 btn btn-sm btn-outline-warning"><i class="fa-regular fa-clock"></i></button>';
    $approve_btn = '<button onclick="mod_request(4,\''.$id.'\')" class="mx-1 btn btn-sm btn-outline-success"><i class="fa-solid fa-check"></i></button>';
    $view_btn = '<a href="/'.$c_role.'&v=request&id='.$id.'" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i></a>';
    $delete_btn = '<a onclick="delete_request('.$id.');" class="mx-1 btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></a>';
    $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.$pending_btn.$approve_btn.$delete_btn.'</div>';
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "id":"'.$id.'",
                "fullname":"'.$fullname.'",
                "req_type":"'.$req_type.'",
                "date_requested":"'.$date_req.'",
                "status":"'.$status.'",
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
                { title: "Fullname", field: "fullname", minWidth: 250 },
                { title: "Request Type", field: "req_type", minWidth: 150 },
                { title: "Date Requested", field: "date_requested", minWidth: 200 },
                { title: "Status", field: "status", minWidth: 120 },
                { title: "Action", field: "Action", formatter: "html", minWidth: 200 },
            ],
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("fullname", "like", searchValue);
        }


        function mod_request(mod_type, service_id){
            var formData = new FormData();
            formData.append('mtype', mod_type);
            formData.append('s_id', service_id);

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
                            toast('Request moved to Pending! ','1500','rgb(255,255,0,0.5)');
                            break;
                        case 4:
                            toast('Request Approved! ','1500','rgb(0,255,0,0.5)');
                            break;
                    }
                    table.deleteRow(service_id);
                }
                return response.text();
            }).then(data=>{
                console.log(data);
            })
        }


    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
