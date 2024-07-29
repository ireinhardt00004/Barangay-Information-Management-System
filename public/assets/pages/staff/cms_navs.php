<?php 
$pages_options = '';
foreach(get_all_pages() as $pdata){
    $pages_options.='<option value="'.$pdata['id'].'">'.base64_decode($pdata['page_name']).'</option>';
}
$pages_options = base64_encode($pages_options);
?>

    <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
        <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
        <a class="mx-2 btn btn-sm btn-outline-primary rounded-pill" onclick="create_nav('<?=$pages_options;?>');"><i class="fa-solid fa-link"></i> New Nav</a>
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
$navs_data = get_all_navs();
$last_data = end($navs_data);
foreach($navs_data as $data){
    $nav = $data['nav_name'];
    $page_id = intval($data['page_id']);
    $nav_id = intval($data['id']);
    $page = base64_decode(data_extract($page_id, 'id', get_all_pages())['page_name']);
    
    $id = $data['id'];
    $btns = '<div id="'.$id.'" class="d-flex justify-content-center"><button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_l(\'nav\','.$id.');"><i class="fas fa-trash-alt"></i></button><button class="mx-1 btn btn-sm btn-outline-warning act-btn" onclick="edit_nav(\''.$pages_options.'\',\''.$nav.'\',\''.$page.'\',\''.$nav_id.'\');"><i class="fas fa-edit"></i></button></div>';
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "nav":"'.$nav.'",
                "page":"'.$page.'",
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
                { title: "Nav Name", field: "nav" },
                { title: "Linked Page", field: "page" },
                { title: "Action", field: "Action", formatter: "html", width: 100 },
            ],
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("nav", "like", searchValue);
        }

    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    