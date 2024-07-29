<?php 

$pages_options = '';
foreach(get_all_pages() as $pdata){
    $pages_options.='<option value="'.$pdata['id'].'">'.base64_decode($pdata['page_name']).'</option>';
}
$pages_options = base64_encode($pages_options);
?>
    <p>The <b>staff</b> changed the <b>status</b> of a request from the <b>tracking code</b>: </p>
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
$navs_data = get_all_mon_logs();
$last_data = end($navs_data);
foreach($navs_data as $data){
    $id = intval($data['id']);
    try{
        $email = data_extract(strval($data['staff_id']), 'uid', get_all_data(1));
        $tc = data_extract(intval($data['service_id']), 'id', get_all_services());
        $status = data_extract(intval($data['service_id']), 'id', get_all_services());
        if(is_array($email) &&  is_array($tc) && is_array($status)){
            $email = $email['email'];
            $tc = $tc['tracking_code'];
            $status = $status['status'];
        }else{
            continue;
        }

    }catch(Exception $e){
        continue;
    }
    $date_modified = $data['date_created'];
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "id":"'.$id.'",
                "email":"'.$email.'",
                "tracking_code":"'.$tc.'",
                "status":"'.$status.'",
                "date_mod":"'.$date_modified.'",
            }'.$endz;
}
?>

        ];

        var table = new Tabulator("#table-container", {
            data: tableData,
            placeholder: 'Empty Data',
            layout: "fitDataStretch",
            pagination: "local",
            paginationSize: 4,
            height: "100%",
            rowFormatter: function (row) {
                row.getElement().style.height = "60px";
            },
            columns: [
                { title: "Staff Email", field: "email" },
                { title: "Tracking Code", field: "tracking_code", maxWidth: 500 },
                { title: "Status:", field: "status" },
                { title: "Date Modified", field: "date_mod" },
            ],
            initialSort:[
                {column: "date_mod", dir: "desc"}
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
