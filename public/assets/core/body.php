<body>
<?php
$page = './assets/pages/';
$view_n = $_SESSION['view'] = 'home';
if(isset($_GET['p'])){
    $p_view = strtolower($_GET['p']);
    $home_pages = ['home','index.php','index.html'];
    $other_pages = ['verify','report_request','track_request','make_request','about','contact','programs','announcements','service','login','resident','staff','view_program','staff_login','admin','admin_login'];
    // $navs_pages = [];
    $cstm_pages = [];

    // $navs_data = get_all_navs();
    // $last_data = end($navs_data);
    // foreach($navs_data as $data){
    //     $nav = $data['nav_name'];
    //     $page_id = intval($data['page_id']);
    //     $nav_id = $data['id'];
    //     $npage = base64_decode(data_extract($page_id, 'id', get_all_pages())['page_name']);
    //     array_push($navs_pages, $npage);
    // }
    foreach(get_all_pages() as $cpage){
        $page_name = base64_decode($cpage["page_name"]);
        // echo $page_name;exit;
        array_push($cstm_pages, $page_name);
    }

    if(empty($p_view)||in_array($p_view, $home_pages)){
        $view_n ='home';
    }elseif(in_array($p_view, $other_pages)){
        $view_n = $p_view;
    }elseif(in_array($p_view, $cstm_pages)){
        $view_n = 'cstm_view';
    }else{
        $view_n = '404';
    }
}
$view = $page.$view_n.'.php';

if(!file_exists($view)){$view = $page.'home.php';}
include($view);
?>
</body>

