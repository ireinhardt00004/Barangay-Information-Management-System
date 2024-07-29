<?php
$page = './assets/pages/members/';
$view_n = $_SESSION['view_m'] = 'home';

isLogged();

$_SESSION["err"] = "";
$user_data = data_extract($_SESSION['sess_uid'],'uid', get_all_data(3));
$user_photo = 'img1.png';
$user_name = $user_data["firstname"].' '.$user_data["lastname"];
$status = $user_data["verified"];
$valid_id = $user_data["valid_id"];

if(!empty($user_data['photo'])){
   $user_photo = base64_decode($user_data['photo']);
}

function get_rn_no(){
   $uuid = $_SESSION["sess_uid"];
 
   $conn = $GLOBALS['conn'];
   $sql = $conn->prepare("SELECT req_no FROM `members` WHERE uid = ?;");
   $sql->execute([$uuid]);
   $result = $sql->fetch(PDO::FETCH_ASSOC)['req_no'];
   return $result;
}

if(isset($_POST['mtype'])){

   $uuid = $_SESSION['sess_uid'];
   $currentDate = date('Y-m-d H:i:s', strtotime('now'));
   $mod_type = $_POST['mtype'];
   $service_id = $_POST['s_id'];
   $date_approve = '';
   $new_rn = get_rn_no();
   if($new_rn > 0){
      $new_rn = get_rn_no() - 1;
   }

   switch($mod_type){
       case 2:
           $status = "cancelled";
           update_seeker(2, $uuid, 0);
           break;
   }
   $sql = $conn->prepare("UPDATE `services` SET status=:status, date_modified=:da WHERE id=:id;");
   $sql->bindParam(":id",$service_id);
   $sql->bindParam(":status",$status);
   $sql->bindParam(":da",$date_approve);
   $sql->execute();

   $sql = $conn->prepare("UPDATE `members` SET req_no=:nrn WHERE uid=:uuid;");
   $sql->bindParam(":nrn", $new_rn);
   $sql->bindParam(":uuid",$uuid);
   $sql->execute();
   exit;
}

if(isset($_GET['v'])){
    $p_view = strtolower($_GET['v']);
    $home_pages = ['home','dashboard','dash'];
    $other_pages = ['settings','requested','request','req_file','reports','programs','messages','cms','news_announcement','monitor_r',''];
    if($p_view === "verify"){
      header("Location: /resident&v=settings");
    }

    if(empty($p_view)||in_array($p_view, $home_pages)){
        $view_n ='home';
    }elseif(in_array($p_view, $other_pages)){
        $view_n = $p_view;
    }else{
        $view_n = '404';
    }
}
if(isset($_GET['logout'])){
   logout();
}

if(isset($_POST['fetch_msgs'])){
   $member_id = $_POST['member_id'];
   $sql = $conn->prepare("SELECT * FROM messages WHERE member_id=:id;");
   $sql->bindParam(':id',$member_id);
   $sql->execute();
   $results = $sql->fetchAll(PDO::FETCH_ASSOC);
   $all_msgs = [];
   foreach($results as $res){
      $staff_m = $res['receiver_id'];
      $mem_m = $res['member_id'];
      $msg = $res['message'];
      $timestampz = $res['timestamp'];
      $timestampDateTime = new DateTime($timestampz);
      $ts = $timestampDateTime->format('g:ia M j Y');
 
      if($staff_m != 0){
         $msg_type = 'left';
      }else{
         $msg_type = 'right';
      }
      array_push($all_msgs, $msg_type.':'.base64_encode($msg).':'.base64_encode($ts));
   }

   echo json_encode($all_msgs);
   exit;
 }

$view = $page.$view_n.'.php';
if(!file_exists($view)){$view = $page.'home.php';}
?>
<script>
function toast(msg, time,bgColor){
    Toastify({
  text: msg,
  duration: time,
  close: true,
  gravity: "top", 
  position: "right", 
  stopOnFocus: true, 
  style: {
    background: bgColor,
  },
}).showToast();    
}

</script>
<div class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark">
   <button class="border-0 btn order-lg-0 ms-2 m-0 me-lg-0 text-black" style="background-color:rgb(255, 245, 153)!important;" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
   <a class="ms-3 navbar-brand d-flex align-items-center text-white fw-bold" href="home">
      <img height="50" src="<?=$web_logo?>" alt="Logo">
      <div class="d-md-block ps-2">
         <p class="mb-0 cstm-text-sm" style="font-size: 14px"><?=$web_title;?></p>
      </div>
   </a>
</nav>
<div id="layoutSidenav">
<div id="layoutSidenav_nav">
<nav class="sb-sidenav accordion sb-sidenav" style="background-color:white;border-right:1px solid rgb(0,0,0,0.2);" id="sidenavAccordion">
   <div class="sb-sidenav-menu">
      <div class="nav">
         <div>
            <img class="img-fluid" src="./assets/imgs/bg-img1.png">
         </div>
         <div class="sb-sidenav-menu-heading">Main Navigation</div>
         <a class="nav-link" href="resident&v=home">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fas fa-house-chimney-window"></i></div>
            Dashboard
         </a>
         <a class="nav-link" href="resident&v=requested">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
            Request file
         </a>
         <!-- <a class="nav-link" href="">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-folder-closed"></i></div>
            Files Requested
         </a> -->
         <a class="nav-link" href="resident&v=programs">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-code-pull-request"></i></div>
            Programs
         </a>
         <a class="nav-link" href="resident&v=messages">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-regular fa-comments"></i></div>
            Messages
         </a>
         <a class="nav-link" href="/announcements">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-bullhorn"></i></div>
            News And Announcement
         </a>
         <a class="nav-link" href="resident&v=settings">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-gears fa-rotate-90"></i></div>
            Settings
         </a>
      </div>
   </div>
   <div class="sb-sidenav-footer">
      <div class="dropdown">
         <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
         <img src="./assets/imgs_uploads/<?=$user_photo;?>" alt="" width="32" height="32" class="rounded-circle me-2">
         <strong><?=$user_name;?></strong>
         </a>
         <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
            <li><a class="dropdown-item" href="resident&v=settings">Settings</a></li>
            <li>
               <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="resident&logout">Sign out</a></li>
         </ul>
      </div>
</nav>
</div>
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
   <main>
      <div class="container-fluid px-4">
         <div class="d">
            <h1 class="mt-4">Dashboard | <span style="font-size:22px;"><?=strtoupper($view_n);?></span></h1>
         </div>
         <hr style="border:1px solid black;">
         <?php include($view);?>
   </main>
   </div>
   </div>
   <script src="./assets/js/sb-script.js"></script>
   <link href="./assets/css/sb-style.css" rel="stylesheet" />
</div>
<script>
const navLinks = document.querySelectorAll('.nav-link');

const uri = window.location.href;
const uriParts = uri.split('=');
const uriValue = uriParts[uriParts.length - 1];

navLinks.forEach(link => {
   const href = link.getAttribute('href');
   const hrefParts = href.split('=');
   const linkValue = hrefParts[hrefParts.length - 1];

   if (linkValue === uriValue) {
      link.classList.add('active-nav');
   }
});
</script>

<link rel="stylesheet" type="text/css" href="./assets/style.css">

<!-- head: title, button1, our story, announcement, programs -->


