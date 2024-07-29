<?php 
function get_maxrn_no(){

    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT max_requests FROM `general_conf`;");
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC)['max_requests'];
    return intval($result);
}

$navs_data = get_all_services();
$last_data = end($navs_data);
$total_pending = 0;
foreach($navs_data as $data){
    $id = $data["id"];
    $uuid = $_SESSION['sess_uid'];
    $status = $data['status'];
    $data_uid = $data['user_id'];

    if(strval($data_uid) === strval($uuid) && $status !== "declined"){
        $sql = $conn->prepare("UPDATE `services` SET comment='' WHERE id=:s_id;");
        $sql->bindParam(":s_id",$id);
        $sql->execute();
    }

    if(strval($data_uid) === strval($uuid) && $status === "pending"){
        $total_pending+=1;
    }

}

$sql = $conn->prepare("UPDATE `members` SET req_no=:nrn WHERE uid=:uuid;");
$sql->bindParam(":nrn", $total_pending);
$sql->bindParam(":uuid",$uuid);
$sql->execute();

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
    <small>Note you only have <?=get_maxrn_no();?> max request, you can request again if they have approved/declined your requests.</small>
    <div class="container-fluid bg-light p-2">
        <a href="/make_request" class="btn <?=(get_rn_no() === get_maxrn_no())?"btn-secondary":"btn-primary";?>" <?=(get_rn_no() === get_maxrn_no())?"onclick='toast(\"You have max requests!\", 2000, \"rgb(200,0,0,0.4)\");return false;'":"";?>>Request new</a>
        <a class="text-decoration-none text-black mx-2">Request left: <span class="fw-bold"><?=get_rn_no();?>/<?=get_maxrn_no();?></span></a>
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
$uuid = $_SESSION["sess_uid"];
$navs_data = get_all_services();
$last_data = end($navs_data);

foreach($navs_data as $data){
    $status = $data['status'];
    $data_uid = $data['user_id'];

    if(strval($data_uid) !== strval($uuid)){
        continue;
    }

    $id = $data['id'];
    // $fullname = $data['fullname'];
    $req_type = data_extract(intval($data['request_type']), 'id', get_all_rtypes())?data_extract(intval($data['request_type']), 'id', get_all_rtypes())['request_type']:'';
    $date_req = $data['date_created'];
    $comment = trim($data['comment']);
    $delete_btn = '<a onclick="delete_request('.$id.');" class="mx-1 btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i> Delete</a>';
    $cancel_btn = '<button onclick="mod_request(2,\''.$id.'\')" class="mx-1 btn btn-sm btn-outline-warning"><i class="fa-solid fa-x"></i> Cancel</button>';
    $view_btn = '<a href="/resident&v=request&id='.$id.'" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i> View</a>';

    if($status === "cancelled" || $status === "declined" || $status === "approved"){
        $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.'</div>';
    }elseif($status !== "declined"){
        $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.$cancel_btn.'</div>';
    }

    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "id":"'.$id.'",
                "req_type":"'.$req_type.'",
                "date_requested":"'.$date_req.'",
                "status":"'.$status.'",
                "comment":"'.$comment.'",
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
                // { title: "Fullname", field: "fullname" , minWidth: 250},
                { title: "Request Type", field: "req_type", minWidth: 150 },
                { title: "Date Requested", field: "date_requested", minWidth: 200 },
                { title: "Status", field: "status", minWidth: 120 },
                { title: "Comment", field: "comment", minWidth: 120 },
                { title: "Action", field: "Action", formatter: "html", minWidth: 200 },
            ],
            initialSort: [
                { column: "date_requested", dir: "desc" } // Sort by Date Requested column in descending order
            ]
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("fullname", "like", searchValue);
        }

        function mod_request(mod_type, service_id){
            var formData = new FormData();
            formData.append('mtype', mod_type);
            formData.append('s_id', service_id);

            fetch("/resident",{
                method: 'POST',
                body: formData
            }).then(response=>{
                if(!response.ok){
                    toast('Error! ','2000','rgb(255,0,0,0.5)');
                }else{
                    switch(mod_type){
                        case 2:
                            toast('Cancelled! ','1500','rgb(0,0,200,0.5)');
                            break;
                        case 3:
                            break;
                    }
                }
                return response.text();
            }).then(data=>{
                location.reload();
                console.log(data);
            })
        }

    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

