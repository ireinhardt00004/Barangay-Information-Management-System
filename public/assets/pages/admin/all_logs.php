<?php 

$pages_options = '';
foreach(get_all_pages() as $pdata){
    $pages_options.='<option value="'.$pdata['id'].'">'.base64_decode($pdata['page_name']).'</option>';
}
$pages_options = base64_encode($pages_options);
?>
    <p>All activity logs except for <a class="fw-bold text-decoration-none" href="/<?=$c_role;?>&v=mon_logs">requests logs</a> </p>
    <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
        <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search email...">
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
$navs_data = get_all_a_logs();
$last_data = end($navs_data);
foreach($navs_data as $data){
    $uid = $data['uid'];
    $user = $data['user'];
    $type = $data['page'];
    $acty = $data['activity'];
    $ts = $data['timestamp'];

    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "uid":"'.$uid.'",
                "email":"'.$user.'",
                "type":"'.$type.'",
                "ts":"'.$ts.'",
                "activity":"'.$acty.'",
            }'.$endz;
}
?>

        ];

        var table = new Tabulator("#table-container", {
            data: tableData,
            placeholder: 'Empty Data',
            layout: "fitDataStretch",
            pagination: "local",
            paginationSize: 10,
            height: "100%",
            rowFormatter: function (row) {
                row.getElement().style.height = "60px";
            },
            columns: [
                { title: "user_id", field: "uid" },
                { title: "Timestamp", field: "ts" },
                { title: "User", field: "email" },
                { title: "Activity", field: "activity"},
                { title: "Path", field: "type" },
            ],
            initialSort:[
                {column: "ts", dir: "desc"}
            ],
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("email", "like", searchValue);
        }


        function mod_request(mod_type, service_id){
            var formData = new FormData();
            formData.append('mtype', mod_type);
            formData.append('s_id', service_id);

            fetch("/staff",{
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
