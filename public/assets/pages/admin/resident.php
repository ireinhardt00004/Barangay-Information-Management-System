
<div class="shadow search-btn d-flex p-2">
        <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
    </div>
    <div id="table-container" style="font-size:16px;"></div>
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
$user_datas = get_all_data(2);
$last_data = end($user_datas);
foreach($user_datas as $data){
    $fn = $data['firstname'];
    $ln = $data['lastname'];
    $mn = $data['middlename'];
    $email = $data['email'];
    $ut = 2;
    $uid = $data['uid'];
    // $btns = '<div id="'.$uid.'" class="w-100 d-flex justify-content-center"><button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_user('.$ut.','.$uid.');"><i class="fas fa-trash-alt"></i></button><button class="mx-1 btn btn-sm btn-outline-warning act-btn" onclick="edit_user(\''.$fn.'\',\''.$ln.'\',\''.$mn.'\',\''.$email.'\',\''.$ut.'\',\''.$uid.'\');"><i class="fas fa-edit"></i></button></div>';
    $btns = '<div id="'.$uid.'" class="w-100 d-flex justify-content-center"><button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_user('.$ut.','.$uid.');"><i class="fas fa-trash-alt"></i></button></div>';
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "Firstname":"'.$fn.'",
                "Lastname":"'.$ln.'",
                "Email":"'.$email.'",
                "Date created":"'.$data['date_created'].'",
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
            paginationSize: 10,
            height: "100%",
            columns: [
                { title: "Firstname", field: "Firstname" },
                { title: "Lastname", field: "Lastname" },
                { title: "Email", field: "Email" },
                { title: "Date created", field: "Date created" },
                { title: "Action", field: "Action", formatter: "html", colspan:2, width:100 },
            ],
        });
        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("Firstname", "like", searchValue);
        }
    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.1.5/js/tabulator.min.js"></script>
