
    <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
        <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search name...">
        <a class="mx-2 btn btn-sm btn-outline-primary rounded-pill" href="<?=$c_role;?>&v=cms_new_page"><i class="fa-solid fa-file-lines"></i> New Page</a>
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
$pages_data = get_all_pages();
$last_data = end($pages_data);
foreach($pages_data as $data){
    $page_name = si(base64_decode($data['page_name']));
    $content = si(base64_decode($data['contents']));
    if (strlen($content) > 40){
        $content= substr($content, 0, 37) . '...';
    }
    $id = $data['id'];
    $btns = '<div id="'.$id.'" class="d-flex justify-content-center"><button class="mx-1 btn btn-sm btn-outline-danger act-btn" onclick="delete_l(\'page\','.$id.');"><i class="fas fa-trash-alt"></i></button><a class="mx-1 btn btn-sm btn-outline-warning act-btn" href="'.$c_role.'&v=cms_edit_page&id='.$id.'"><i class="fas fa-edit"></i></button></div>';
    $endz = ',';
    if($data == $last_data){
        $endz = '';
    }

    echo '
            {
                "page":`'.$page_name.'`,
                "content":`'.$content.'`,
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
                { title: "Page Name", field: "page" },
                { title: "Contents", field: "content" },
                { title: "Action", field: "Action", formatter: "html", width: 100 },
            ],
        });

        function search_btn(){
            var searchValue = document.getElementById("search-input").value;
            table.setFilter("page", "like", searchValue);
        }

    </script>
    <script src="./assets/js/a-dash-script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    