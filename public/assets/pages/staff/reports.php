<?php 

if(isset($_POST['delete_report'])){
    $rid = $_POST['report_id'];
    $sql = $conn->prepare("DELETE FROM `report_request` WHERE id=:id;");
    $sql->bindParam(":id",$rid);
    $sql->execute();
    act_logger("Deleted a report | report ID: '$rid' ", $current_uri);

    echo "<script>window.location.href='/$c_role&v=reports';</script>";
}

?>

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
$navs_data = get_all_report_request();
$last_data = end($navs_data);
foreach($navs_data as $data){

    $id = $data['id'];
    $uid = $data['uid'];
    $fullname = $data['fullname'];
    $date_r = $data['date_created'];
    $issue = $data['issue'];
    $view_btn = '<a href="/'.$c_role.'&v=report&id='.$id.'" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i> View Report</a>';
    $delete_btn = '<a onclick="delete_report('.$id.');" class="mx-1 btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can"></i></a>';
    $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.$delete_btn.'</div>';

    if(strlen($issue)>10){
        $issue = trim(substr($issue, 0, 10)).'...';
    }

    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "id":"'.$id.'",
                "fullname":"'.$fullname.'",
                "date_r":"'.$date_r.'",
                "issue":"'.$issue.'",
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
                { title: "Fullname", field: "fullname" },
                { title: "Date Reported", field: "date_r" },
                { title: "Issue", field: "issue" },
                { title: "Action", field: "Action", formatter: "html", width: 200 },
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

    