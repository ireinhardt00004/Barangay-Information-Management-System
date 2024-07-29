<?php
$page = './assets/pages/admin/';
$view_n = $_SESSION['view_m'] = 'home';
$_SESSION["sess_msg"] = "";
isLogged();

$c_role = "admin";
$current_uri = $_SERVER['REQUEST_URI'];

$user_data = data_extract($_SESSION['sess_uid'],'uid', get_all_data(0));
$user_photo = base64_decode($user_data['photo']);
if(empty($user_photo)){
   $user_photo = 'img1.png';
}
$dash_titlex = "home";

function get_time_dif($ts)
{
   $timestampDateTime = new DateTime($ts);
   $currentDateTime = new DateTime();
   $interval = $timestampDateTime->diff($currentDateTime);

   $res = '';

   if ($interval->y > 0){
      $res = $interval->y . 'yrs ago';
   }
   elseif ($interval->m > 0){
      $res = $interval->m . 'mos ago';
   } 
   elseif ($interval->d >= 7){
      $res = floor($interval->d / 7) . 'w ago';
   }
   elseif ($interval->d > 0){
      $res = $interval->d . 'd ago';
   }
   elseif ($interval->h > 0){
      $res = $interval->h . 'h ago';
   }
   elseif ($interval->i > 0){
      $res = $interval->i . 'm ago';
   }
   elseif ($interval->s > 0) {
      $res = $interval->s . 's ago';
   }
   else{
      $res = 'Just now';
   }

   return $res;
}

if(isset($_GET['v'])){
    $p_view = strtolower($_GET['v']);
    $home_pages = ['home','dashboard','dash'];
    $other_pages = ['mon_logs','settings','all_logs','staff','resident'];
    $other_pages2 = ['verifies', 'edit_record','view_record','new_record','records','report','reports','create_program','edit_program','programs','news_announcement','create_announcement','edit_announcement','messages','cms_edit_page','cms_navs','cms_new_page','cms_pages','cms_general','settings','monr_pr','monr_ar','monr_dr','request'];

    if(empty($p_view)||in_array($p_view, $home_pages)){
        $view_n ='home';
        $dash_titlex = ucwords($p_view);
    }elseif(in_array($p_view, $other_pages)){
         $view_n = $p_view;
         $dash_titlex = ucwords($p_view);
    }elseif(in_array($p_view, $other_pages2)){
         $view_n = '../staff/'.$p_view;
         $dash_titlex = ucwords($p_view);
    }else{
         $view_n = '404';
         $dash_titlex = ucwords($p_view);
    }
}

if(isset($_POST['user_create'])){
   $email = $_POST['email'];
   $user_type = intval($_POST['user_type']);

   $fn = $_POST['fname'];
   $ln = $_POST['lname'];
   $mn = $_POST['mname'];

   $new_pass = gen_passwd();
   $password = $new_pass;
   $content = "<table width='100%' border='0' cellspacing='0' cellpadding='20' style='max-width: 600px; margin: 50px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'> <tr> <td> <h1 style='color: #333;'>Thank You for Registering</h1> <p style='color: #555;'>Hello $email,</p> <p style='color: #555;'>Thank you for registering. Here is your temporary password. Please change it after you log in successfully.</p> <p style='color: #555;'>Your temporary password is: <strong>$new_pass</strong></p>  <p style='color: #555;'>Thank you!</p> </td> </tr> </table>";

   $uid = gen_uid();
   $hash_verif = generateHexId(17);

   $status = create_user($user_type, $uid, $fn, $ln, $mn, $email, $password,'aW1nMS5wbmc=', $hash_verif);
   if($status !== 'EmailExist'){
      $_SESSION['sess_msg'] = "toast('Success!','2000','rgb(0,200,0,0.5)');";
      
      $current_host = $_SERVER['SERVER_NAME']; 
      $current_protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
      $data = dataCrypt("1:$hash_verif", 13, 1);
      $host = $current_protocol.$current_host.'/verify&token='.$data.'&nonce='.md5($data);
      $content = "<table width='100%' border='0' cellspacing='0' cellpadding='20' style='max-width: 600px; margin: 50px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'><tr><td><h1 style='color: #333;'>Complete Account Setup</h1><p style='color: #555;'>Hello $email,</p><p style='color: #555;'>Click continue to complete account setup.</p><a href='$host' style='color:#1b74e4;text-decoration:none;display:block;width:270px' target='_blank' data-saferedirecturl='https://www.google.com/url?q=$host'><table border='0' width='290' cellspacing='0' cellpadding='0' style='border-collapse:collapse'><tbody><tr><td style='border-collapse:collapse;border-radius:3px;text-align:center;display:block;border:solid 1px #009fdf;padding:10px 16px 14px 16px;margin:0 2px 0 auto;min-width:80px;background-color:#47a2ea'><a href='$host' style='color:#1b74e4;text-decoration:none;display:block' target='_blank' data-saferedirecturl='https://www.google.com/url?q=$host'><center><font size='3'><span style='font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;white-space:nowrap;font-weight:bold;vertical-align:middle;color:#fdfdfd;font-size:16px;line-height:16px'>&nbsp;<span class='il'>Continue</span></span></font></center></a></td></tr></tbody></table></a><p style='color: #555;'>Thank you!</p></td></tr></table>";
      send_mail($email, "Complete account setup", $content);

   }else{
      $_SESSION['sess_msg'] = "toast('Failed creating user, the same email already exists!','8000','rgb(200,0,0,0.5)');";
   }
}

if(isset($_POST['edit_user'])){
   $email = $_POST['email'];
   $ut = intval($_POST['user_type']);

   $fn = $_POST['fname'];
   $ln = $_POST['lname'];
   $mn = $_POST['mname'];
   $uid = $_POST['uid'];
   $passw = $_POST['password'];

   if(empty(trim($passw))){
      $passw = gudbid($ut, $uid)[0]["password"];
   }else{
      $passw = enc($passw);
   }

   $status = update_user($ut, $uid, $fn, $ln, $mn, $email, $passw);
   if($status){
      $_SESSION['sess_msg'] = "toast('Success!','2000','rgb(0,200,0,0.5)');"; 
   }else{
      $_SESSION['sess_msg'] = "toast('Failed!','2000','rgb(200,0,0,0.5)');";
   }
}


if(isset($_POST['delete_user'])){
   $uid = intval($_POST['uid']);
   $ut = intval($_POST['user_type']);
   if($ut > 1){
      delete_chat($uid);
   }
   delete_user($ut, $uid);
}

if (isset($_POST['save_recordz'])) {
   foreach ($_POST as $key => $value) {
       $_POST[$key] = si($_POST[$key]);
   }
   $address = $_POST['address'];
   $cellphone = $_POST['cellphone'];
   $householdNumber = $_POST['householdNumber'];
   $housingType = $_POST['housingType']; 
   $housingType2 = $_POST['housingType2'];
   $kuryente = $_POST['kuryente'];
   $tubig = $_POST['tubig'];
   $palikuran = $_POST['palikuran'];

   $table_data = $_POST['tableData'];

   $sql = $conn->prepare("INSERT INTO `records` (address, cellphone, householdNumber, housingType, housingType2, kuryente, tubig, palikuran, table_data) VALUES (:address, :cp, :hhn, :ht, :ht2, :kuryente, :tubig, :palikuran, :table_data)");
   $sql->bindParam(":address", $address);
   $sql->bindParam(":cp", $cellphone);
   $sql->bindParam(":hhn", $householdNumber);
   $sql->bindParam(":ht", $housingType);
   $sql->bindParam(":ht2", $housingType2);
   $sql->bindParam(":kuryente", $kuryente);
   $sql->bindParam(":tubig", $tubig);
   $sql->bindParam(":palikuran", $palikuran);
   $sql->bindParam(":table_data", $table_data);
   $sql->execute();
   exit;
}

if (isset($_POST['edit_recordz'])) {
  foreach ($_POST as $key => $value) {
      $_POST[$key] = si($_POST[$key]);
  }
  $id = $_POST['id'];
  $address = $_POST['address'];
  $cellphone = $_POST['cellphone'];
  $householdNumber = $_POST['householdNumber'];
  $housingType = $_POST['housingType']; 
  $housingType2 = $_POST['housingType2'];
  $kuryente = $_POST['kuryente'];
  $tubig = $_POST['tubig'];
  $palikuran = $_POST['palikuran'];

  $table_data = $_POST['tableData'];

  $sql = $conn->prepare("UPDATE `records` SET address = :address, cellphone = :cp, householdNumber = :hhn, housingType = :ht, housingType2 = :ht2, kuryente = :kuryente, tubig = :tubig, palikuran = :palikuran, table_data = :table_data WHERE id = :id");
  $sql->bindParam(":id", $id);
  $sql->bindParam(":address", $address);
  $sql->bindParam(":cp", $cellphone);
  $sql->bindParam(":hhn", $householdNumber);
  $sql->bindParam(":ht", $housingType);
  $sql->bindParam(":ht2", $housingType2);
  $sql->bindParam(":kuryente", $kuryente);
  $sql->bindParam(":tubig", $tubig);
  $sql->bindParam(":palikuran", $palikuran);
  $sql->bindParam(":table_data", $table_data);
  $sql->execute();
  exit;
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
  // Mark as read/remove bold  
  $sql = $conn->prepare("UPDATE messages set isRead=1 WHERE member_id=:id;");
  $sql->bindParam(':id',$member_id);
  $sql->execute();
  echo json_encode($all_msgs);
  exit;
}

if(isset($_POST['fetch_chats'])){
  $sql = $conn->prepare("SELECT m1.* FROM messages m1 INNER JOIN ( SELECT member_id, MAX(timestamp) AS latest_timestamp FROM messages GROUP BY member_id ) m2 ON m1.member_id = m2.member_id AND m1.timestamp = m2.latest_timestamp ORDER BY m1.id DESC;");
  $sql->execute();
  $results = $sql->fetchAll(PDO::FETCH_ASSOC);
  $all_chats = [];

  foreach($results as $res){
     $member_id = $res['member_id'];
     $member_fn = data_extract($member_id, 'id', get_all_data(2))['firstname'];
     $member_ln = data_extract($member_id, 'id', get_all_data(2))['lastname'];
     $photo = data_extract($member_id, 'id', get_all_data(2))['photo'];
     $m_fullname = $member_fn.' '.$member_ln;
     $sender_who = ($res['receiver_id'] == 0)? $m_fullname.': ':'You: ';
     $last_msg = $res['message'];
     $timestampz = base64_encode(get_time_dif($res['timestamp']));
     $isReadz = ($res['isRead'] === 0)?base64_encode('fw-bold'):'IA=='; # IA== is base64encoded space
     $photo = (empty($photo))?'img1.png':base64_decode($photo);

     if(strlen($last_msg) > 18){
        $last_msg = trim(substr($last_msg, 0, 18)).'...';
     }
     $photoz = base64_encode($photo);
     $last_msg = $sender_who.$last_msg;
     $chatz = base64_encode($member_id).':'.base64_encode($m_fullname).':'.base64_encode($last_msg).':'.$photoz.':'.$timestampz.':'.$isReadz;
     array_push($all_chats, $chatz);
  }
  echo json_encode($all_chats);
  exit;
}

if(isset($_POST['mtype2'])){
   $staff_uid = $_SESSION['sess_uid'];
   $mod_type = intval($_POST['mtype2']);
   $uid = $_POST['uuid'];

   if($mod_type === 2){
       $sql = $conn->prepare("UPDATE `members` SET verified='false', valid_id='none' WHERE uid=:uid;");
   }elseif($mod_type === 3){
       $sql = $conn->prepare("UPDATE `members` SET verified='true' WHERE uid=:uid;");
   }
   $sql->bindParam(":uid",$uid);
   $sql->execute();
   exit;
}


if(isset($_POST['mtype'])){
   $staff_uid = $_SESSION['sess_uid'];
   $currentDate = date('Y-m-d H:i:s', strtotime('now'));
   $mod_type = $_POST['mtype'];
   $service_id = $_POST['s_id'];
   $date_modified = $currentDate;
   switch($mod_type){
       case 2:
           $status = "declined";
           break;
       case 3:
           $status = "pending";
           break;
       case 4:
           $status = "approved";
           break;
   }
   $sql = $conn->prepare("UPDATE `services` SET status=:status, date_modified=:da WHERE id=:id;");
   $sql->bindParam(":id",$service_id);
   $sql->bindParam(":status",$status);
   $sql->bindParam(":da",$date_modified);
   $sql->execute();

   $sql = $conn->prepare("INSERT INTO `monitoring_logs`(service_id, staff_id, status) VALUES(:service_id, :staff_uid, :status);");
   $sql->bindParam(":service_id",$service_id);
   $sql->bindParam(":staff_uid",$staff_uid);
   $sql->bindParam(":status",$status);
   $sql->execute();
   exit;
}

if(isset($_POST['decline'])){
   $staff_uid = $_SESSION['sess_uid'];
   $currentDate = date('Y-m-d H:i:s', strtotime('now'));
   $service_id = $_POST['s_id'];
   $reason = $_POST['reason'];
   $status = "declined";

   $sql = $conn->prepare("UPDATE `services` SET status=:status, date_modified=:da, comment=:reason WHERE id=:id;");
   $sql->bindParam(":id",$service_id);
   $sql->bindParam(":status",$status);
   $sql->bindParam(":reason",$reason);
   $sql->bindParam(":da",$currentDate);
   $sql->execute();

   $sql = $conn->prepare("INSERT INTO `monitoring_logs`(service_id, staff_id, status) VALUES(:service_id, :staff_uid, :status);");
   $sql->bindParam(":service_id",$service_id);
   $sql->bindParam(":staff_uid",$staff_uid);
   $sql->bindParam(":status",$status);
   $sql->execute();
}


if(isset($_POST['save_md'])){
  $content = $_POST['mdcont'];
  $pn = $_POST['page_name'];
  $na_chars = ['','.',"'",'"','/',',','+','|','~','`','^','<','>','(',')','{','}','[',']',';',':','=','*','%','$','#','@','!',' ','\\'];
  foreach(str_split(trim(base64_decode($pn))) as $letter){
     if(in_array($letter, $na_chars)){
        exit;
     }
  }
  $nameExist = data_extract($pn, 'page_name', get_all_pages());
  if($nameExist === False){
     new_l('page', $pn, $content);
     echo "true";
     exit;
  }else{
     echo "exists";
     exit;
  }
}

if(isset($_POST['save_md_changes'])){
  $id = intval($_POST['id']);
  $content = $_POST['mdcont'];
  $pn = $_POST['page_name'];
  $na_chars = ['.',"'",'"','/',',','+','|','~','`','^','<','>','(',')','{','}','[',']',';',':','=','*','%','$','#','@','!',' ','\\'];
  foreach(str_split(base64_decode($pn)) as $letter){
     if(in_array($letter, $na_chars)){
        exit;
     }
  }
  $nameExist = data_extract($pn, 'page_name', get_all_pages());
  if($nameExist === False || intval($nameExist['id']) === $id ){
     update_l('page', $id, $pn, $content);
     echo "true";
     exit;
  }else{
     echo "exists";
     exit;
  }
}

if(isset($_POST['create_nav'])){
  if(isset($_POST['nav_page']) && isset($_POST['nav_name'])){
     $nav_name = si($_POST['nav_name']);
     $page_id = si($_POST['nav_page']);
     $nameExist = data_extract($nav_name, 'nav_name', get_all_navs());
     if($nameExist === False){
        new_l('nav', $nav_name, $page_id);
     }else{
        $_SESSION['err'] = "toast('Nav name already exists! ','2000','rgb(255,0,0,0.5)')";
     }
  }else{
     $_SESSION['err'] = "toast('Nav page cannot be empty! ','2000','rgb(255,0,0,0.5)')";
  }
}

if(isset($_POST['edit_nav'])){
  if(isset($_POST['nav_page']) && isset($_POST['nav_name'])){
     $nav_id = $_POST['nav_id'];
     $nav_name = si($_POST['nav_name']);
     $page_id = si($_POST['nav_page']);
     $nameExist = data_extract($nav_name, 'nav_name', get_all_navs());
     if($nameExist === False || $nameExist['id'] === $nav_id){
        update_l('nav', $nav_id, $nav_name, $page_id);
     }else{
        $_SESSION['err'] = "toast('Nav name already exists! ','2000','rgb(255,0,0,0.5)')";
     }
  }else{
     $_SESSION['err'] = "toast('Nav page cannot be empty! ','2000','rgb(255,0,0,0.5)')";
  }
}


if(isset($_POST['delete_l'])){
  $id = $_POST['id'];
  $type = $_POST['type'];
  $status = delete_l($type, $id);
  if($status){
     $_SESSION['err'] = "toast('Delete Success!','2000','rgb(0,200,0,0.5)')";
  }else{
     $_SESSION['err'] = "toast('Delete Error! ','2000','rgb(255,0,0,0.5)')";
  }
}


if(isset($_GET['logout'])){
   logout();
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

<?php

echo $_SESSION["sess_msg"];

?>
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
         <a class="nav-link" href="admin&v=home">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fas fa-house-chimney-window"></i></div>
            Dashboard
         </a>
         <a class="nav-link" href="admin&v=staff">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-users"></i></div>
            Staff
         </a>
         <a class="nav-link" href="admin&v=resident">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-users"></i></div>
            Resident
         </a>
         <a class="nav-link" href="admin&v=settings">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-gears fa-rotate-90"></i></div>
            Settings
         </a>
         <div class="sb-sidenav-menu-heading">Monitoring Request</div>
         <a class="nav-link" href="admin&v=mon_logs">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-regular fa-paste"></i></div>
            Requests logs
         </a>
         <a class="nav-link" href="admin&v=all_logs">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-shoe-prints"></i></div>
            All logs
         </a>
         <div class="sb-sidenav-menu-heading">Website</div>
         <a class="nav-link" href="admin&v=verifies">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-user-check"></i></div>
            Validate Accounts
         </a>
         <a class="nav-link" href="admin&v=records">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
            Records
         </a>
         <a class="nav-link" href="admin&v=reports">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
            Reports
         </a>
         <a class="nav-link" href="admin&v=programs">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-code-pull-request"></i></div>
            Programs
         </a>
         <a class="nav-link" href="admin&v=messages">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-regular fa-comments"></i></div>
            Messages
         </a>
         <a class="nav-link collapsed" id="news_anoucement" href="#" data-bs-toggle="collapse" data-bs-target="#cms_dropdown" aria-expanded="false" aria-controls="cms_dropdown">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-file-pen"></i></div>
            Content Management
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
         </a>
         <div class="collapse news_anoucement" id="cms_dropdown" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav" style="background-color:rgb(238, 216, 137,0.4);">
                <a class="nav-link" href="admin&v=cms_general">General</a>
                <a class="nav-link" href="admin&v=cms_navs">Nav links</a>
                <a class="nav-link" href="admin&v=cms_pages">Pages</a>
                <a class="nav-link d-none" href="admin&v=cms_new_page"></a>
                <a class="nav-link d-none" href="admin&v=cms_edit_page"></a>
            </nav>
         </div>
         <a class="nav-link" href="admin&v=news_announcement">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-bullhorn"></i></div>
            Announcement
         </a>
         <a class="nav-link collapsed" id="monr" href="#" data-bs-toggle="collapse" data-bs-target="#monr2_dropdown" aria-expanded="false" aria-controls="monr2_dropdown">
            <div class="sb-nav-link-icon"><i class="cstm-colorz fa-solid fa-code-pull-request"></i></div>
            Monitoring Request
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
         </a>
         <div class="collapse monr" id="monr2_dropdown" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav" style="background-color:rgb(238, 216, 137,0.4);">
                <!-- <a class="nav-link" href="admin&v=monr_wir">Walk in request</a> -->
                <a class="nav-link" href="admin&v=monr_pr">Pending Request</a>
                <a class="nav-link" href="admin&v=monr_ar">Approved Request</a>
                <a class="nav-link" href="admin&v=monr_dr">Decline Request</a>
            </nav>
         </div>
         <br>
      </div>
   </div>
   <div class="sb-sidenav-footer">
      <div class="dropdown">
         <a href="#" class="d-flex align-items-center text-black text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
         <img src="./assets/imgs_uploads/<?=$user_photo;?>" alt="" width="32" height="32" class="rounded-circle me-2">
         <strong><?=$user_data['email'];?></strong>
         </a>
         <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
            <li><a class="dropdown-item" href="admin&v=settings">Settings</a></li>
            <li>
               <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="admin&logout">Sign out</a></li>
         </ul>
      </div>
</nav>
</div>
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
   <main>
      <div class="container-fluid px-4">
         <div class="d">
         <!-- <i class="cstm-colorz me-1 fa-solid fa-house"></i> -->
            <h1 class="mt-4">Dashboard | <span style="font-size:22px;"><?=$dash_titlex;?></span></h1>
         </div>
         <hr style="border:1px solid black;">
            <?php include($view);?>
   </main>
   <footer class="py-4 bg-light mt-auto">
   <div class="container-fluid px-4">
   <div class="d-flex align-items-center justify-content-between small">
   <div class="text-muted">Copyright &copy; Brgy Tres Cruses 2023</div>
   <div>
   <!-- <a href="#">Privacy Policy</a>
   &middot;
   <a href="#">Terms &amp; Conditions</a> -->
   </div>
   </div>
   </div>
   </footer>
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

// navLinks.forEach(link => {
//    const href = link.getAttribute('href');
//    const hrefParts = href.split('=');
//    const linkValue = hrefParts[hrefParts.length - 1];
//    if (linkValue === uriValue) {
//       link.classList.add('active-nav');
//       let parent = link.parentNode;
//       if(parent){
//          p = parent.parentNode;
//          p.classList.add('show');
//          pp = document.getElementById("monr");
//          pp.setAttribute('aria-expanded', 'true');
//          pp.classList.remove('collapsed');
//          pp.classList.add('active-parent');
//       }
//    }
// });
navLinks.forEach(link => {
   const href = link.getAttribute('href');
   const hrefParts = href.split('=');
   const linkValue = hrefParts[hrefParts.length - 1];
   if (linkValue === uriValue) {
      link.classList.add('active-nav');
      let parent = link.parentNode;
      const mon_reqs = ['monr_wir','monr_pr','monr_ar','monr_dr','cms_navs','cms_pages','cms_general','cms_new_page'];
      if(parent && mon_reqs.includes(linkValue)){
         p = parent.parentNode;
         let e_id = '';
         if(p.classList.contains('news_anoucement')){
            e_id = "news_anoucement";
         }else if(p.classList.contains('monr')){
            e_id = 'monr';
         }
         p.classList.add('show');
         pp = document.getElementById(e_id);
         pp.setAttribute('aria-expanded', 'true');
         pp.classList.remove('collapsed');
         pp.classList.add('active-parent');
      }
   }
});
</script>

<link rel="stylesheet" type="text/css" href="./assets/style.css">
