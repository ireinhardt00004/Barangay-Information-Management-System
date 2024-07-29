<?php
$default->nav();

function get_maxrn_no(){

  $conn = $GLOBALS['conn'];
  $sql = $conn->prepare("SELECT max_requests FROM `general_conf`;");
  $sql->execute();
  $result = $sql->fetch(PDO::FETCH_ASSOC)['max_requests'];
  return intval($result);
}

function get_rn_no(){
  $uuid = $_SESSION["sess_uid"];

  $conn = $GLOBALS['conn'];
  $sql = $conn->prepare("SELECT req_no FROM `members` WHERE uid = ?;");
  $sql->execute([$uuid]);
  $result = $sql->fetch(PDO::FETCH_ASSOC)['req_no'];
  return $result;
}

function req_no_inc(){
  $uuid = $_SESSION["sess_uid"];
  $ut = $_SESSION['sess_user_type'];
  if( $ut === 0 || $ut === 1){
    return;
  }
  $new_rn = get_rn_no() + 1;
  $conn = $GLOBALS['conn'];
  $sql = $conn->prepare("UPDATE `members` SET req_no=:new_rn WHERE uid=:uid;");
  $sql->bindParam(":uid", $uuid);
  $sql->bindParam(":new_rn", $new_rn);
  $sql->execute();
}

$u_path = "meow";
if(isset($_SERVER['HTTP_REFERER'])){
  $uri = parse_url($_SERVER['HTTP_REFERER']);
  $path = isset($uri['path']) ? $uri['path'] : '';
  if(strtok($path, '&') === "/resident"){
    $u_path = $path;
  }

}

$isLogged = False;
$isVerified = False;
$fullname = "";
$street = "";
$seeker = 0;

if(isset($_SESSION["logged"])){
  if($_SESSION["logged"] === True){
   $isLogged = True;
   $uuid = $_SESSION["sess_uid"];
  //  Start of handle if seeker declined
   $sql = $conn->prepare("SELECT status FROM `services` WHERE user_id=:id AND request_type=3 ORDER BY id DESC LIMIT 1;");
   $sql->bindParam(":id", $uuid);
   $sql->execute();
   $result = $sql->fetch(PDO::FETCH_ASSOC);
   if($result){
    $r_status = $result["status"];
    if($r_status === "declined"){
      update_seeker(2, $uuid, 0);
    }
   }
  //  End of handle if seeker declined

  }
  $uuid = $_SESSION["sess_uid"];
  $user_data = data_extract($uuid, "uid", get_all_data(2));
  $fullname = $user_data["firstname"].' '.$user_data["middlename"].' '.$user_data["lastname"];
  $street = $user_data["address"];

  $status = $user_data["verified"];
  // $seeker = json_decode($user_data["data"])["first_time_seeker"];
  $seeker= $user_data["first_time_seeker"];

  if($status === "true"){
    $isVerified = True;
  }

}

$_SESSION['alert'] = '';
function tcode() {
  $code = '';
  $characters = '013456789ABCDELMNOPSTU';

  for ($i = 0; $i < 6; $i++) {
      for ($j = 0; $j < 5; $j++) {
          $code .= $characters[rand(0, strlen($characters) - 1)];
      }
      if ($i !== 5) {
          $code .= '-';
      }
  }

  return $code;
}
if(!isset($_SESSION['tracking_code'])){
  $_SESSION['tracking_code'] = tcode();
}

if(isset($_POST['new_service'])){
  if($isVerified === False){
    exit;
  }
  if( get_rn_no() !== get_maxrn_no() ){

    foreach($_POST as $key => $value){
      $_POST[$key] = si($_POST[$key]);
    }

    $redirect = "meow";
    if(isset($_POST["rct"])){
      $redirect = $_POST["rct"];
    }

    $rt = $_POST['rtype'];
    $tc = $_SESSION['tracking_code'];
    $dn = "";
    
    if($rt === "0"){
      $fullname = $_POST["fullname"];
      $dob = $_POST["dob"]; // Date of birth
      $age = $_POST["age"]; // age
      $pob = $_POST["pob"]; // Place of birth
      $houseAddr = $_POST["houseAddress"];
      $purpose = $_POST["purpose"];
      $arr_data = array(
        "fullname" => $fullname,
        "dob" => $dob,
        "age" => $age,
        "pob" => $pob,
        "houseAddress" => $houseAddr,
        "purpose" => $purpose 
      );
      $data = json_encode($arr_data);

    }elseif($rt === "1"){
      $fullname = $_POST["fullname"];
      $houseAddr = $_POST["houseAddress"];
      $dor = $_POST["dor"]; // Date of residency
       // Date Needed
      $purpose = $_POST["purpose"];
      $arr_data = array(
        "fullname" => $fullname,
        "houseAddress" => $houseAddr,
        "dor" => $dor,
        "dn" => $dn,
        "purpose" => $purpose 
      );
      $data = json_encode($arr_data);

    }elseif($rt === "2"){
      $fullname = $_POST["fullname"];
      $houseAddr = $_POST["houseAddress"];
       // date needed
      $purpose = $_POST["purpose"];
      $arr_data = array(
        "fullname" => $fullname,
        "houseAddress" => $houseAddr,
        "dn" => $dn,
        "purpose" => $purpose 
      );
      $data = json_encode($arr_data);

    }elseif($rt === "3"){
      $fullname = $_POST["fullname"];
      $houseAddr = $_POST["houseAddress"];
      $dor = $_POST["dor"];
      
      $purpose = $_POST["purpose"];
      $arr_data = array(
        "fullname" => $fullname,
        "houseAddress" => $houseAddr,
        "dor" => $dor,
        "dn" => $dn,
        "purpose" => $purpose 
      );
      $data = json_encode($arr_data);
      if($seeker === 0){
        update_seeker(2, $uuid, 1);
      }

    }elseif($rt === "4"){
      $bn = $_POST["businessName"];
      $bnAddr = $_POST["businessAddress"];
      $owName = $_POST["ownerName"];
      $issueD = $_POST["issuanceDate"];
      $arr_data = array(
        "businessName" => $bn,
        "businessAddress" => $bnAddr,
        "ownerName" => $owName,
        "issuanceDate" => $issueD,
      );
      $data = json_encode($arr_data);

    }elseif($rt === "5"){
      $surname = $_POST["surname"];
      $fn = $_POST["firstName"];
      $mn = $_POST["middleName"];
      $suffix = $_POST["suffix"];
      $houseAddr = $_POST["houseAddress"];
      $civil_status = $_POST["civil_status"];
      $dob = $_POST["dob"]; // date of birth
      $bt = $_POST["bloodType"];
      $religion = $_POST["religion"];
      $cp_num = $_POST["cpNumber"];
      $emName = $_POST["emergencyName"];
      $emNum = $_POST["emergencyContact"];
      $emRS = $_POST["emergencyRelationship"];
      $arr_data = array(
        "surname" => $surname,
        "firstName" => $fn,
        "middleName" => $mn,
        "suffix" => $suffix,
        "houseAddress" => $houseAddr,
        "civil_status" => $civil_status,
        "dob" => $dob,
        "bloodType" => $bt,
        "religion" => $religion,
        "cpNumber" => $cp_num,
        "emergencyName" => $emName,
        "emergencyContact" => $emNum,
        "emergencyRelationship" => $emRS,
      );
      $data = json_encode($arr_data);

    }else{
      echo "[err] invalid params";
      exit();
    }

    $sql = $conn->prepare("INSERT INTO `services`(user_id, request_type, tracking_code, data, status, date_modified, comment) VALUES(:uuid, :rt, :tc, :dat, 'pending','','');");
    $sql->bindParam(":rt", $rt);
    $sql->bindParam(":uuid", $uuid);
    $sql->bindParam(":tc", $tc);
    $sql->bindParam(":dat", $data);
    try {
      $result = $sql->execute();
      if($result){
        $_SESSION['alert'] = "toast('Success!','2000','rgb(0,255,0,0.5)');";
        $_SESSION['tracking_code'] = tcode();
        if($redirect !== "meow"){
          if(strtok($redirect, '&') === "/resident"){
            // header("Location: $redirect");
            // echo "<script>window.location.href='$redirect';</script>";
            // req_no_inc();
            echo "<script>window.location.href='/resident&v=requested';</script>";
          }
        }
      }else{
        $_SESSION['alert'] = "toast('Failed!','2000','rgb(200,0,0,0.5)')";
      }
    } catch (PDOException $e) {
      $err = base64_encode($e);
      $_SESSION['alert'] = "toast('Error! Something went wrong => $err!','2000','rgb(255,0,0,0.5)')";
    }
  }else{
    $_SESSION['alert'] = "toast('[Failed] You have max requests','5000','rgb(200,0,0,0.5)'); setTimeout(function() { window.location.href = '/resident&v=requested'; }, 1000);";
  }

}



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

<?=$_SESSION['alert'];?>

</script>
<div class="h-100">
<div class=" container d-flex align-items-center main-c justify-content-center mb-3">
    <div class="ms-2 col-md-4 w-100 h-100"> <!-- Start of annoucement -->
        <div class="row p-1"><!-- Start of Card Container -->

            <div class="d-flex align-items-center justify-content-center col-md-3 mb-2">
                <div class="shadow pm-box p-2">
                    <img draggable="false" height="200" width="200" src="./assets/imgs/logo.png">
                    <!-- <hr class="m-0 mt-2"> -->
                </div>
            </div>
            <!-- overflow:hidden; border: 1px solid rgb(0,0,0,0.3); -->
            <div class="col p-0" style="background-color: rgb(255,255,255,0.8); border-radius:15px;">
                <div class="h-100 mb-2">
                    <form method="POST">
                      <?php if($u_path !== "meow"){?>
                        <input hidden name="rct" value="<?=$u_path;?>">
                      <?php }?>
                      <div class="input-group mb-2">
                        <span class="input-group-text" style="border-top-left-radius:15px;">Request Type:</span>
                        <select onchange="change_view()" id="service_selc" style="font-weight:bold; border-top-right-radius:15px;" class="text-primary form-select" name="rtype" required>
                          <option value="0" selected>Barangay Clearance</option>
                          <option value="1">Certificate of Residency</option>
                          <option value="2">Certificate of Indigency</option>
<?php if($seeker === 0){?>
                          <option value="3">First time seeker Certification</option>
<?php }?>
                          <option value="4">Brgy Business Clearance</option>
                          <option value="5">Barangay ID</option>
                        </select>
                      </div>
                      <div class="container">
                        <div class="row">
                          <div class="form-group">
                            <label>Tracking Code</label>
                            <input class="form-control" name="tracking_code" value="<?=$_SESSION['tracking_code'];?>" readonly>
                          </div>
                          <!-- Start service view -->
                          <div id="service_view">

                          </div>
                          <!-- End service View -->
                          <div class="mt-4 mb-4">
<?php if($isVerified){?>
                            <small class="d-block py-2 text-danger">Fees per request: ₱<?=$web_data['payment_amt'];?></small>
                            <input class="btn btn-success" type="submit" name="new_service" value="Submit Request">
<?php }elseif($isLogged){?>
                            <small class="text-danger">Account must be verified to use this service*</small><br>
                            <a href="/resident&v=verify" class="btn btn-primary">Get verified</a>
<?php }else{?>
                            <small class="text-danger">You must login and have a verifed account to use this service*</small><br>
                            <a href="/login" class="btn btn-outline-warning">Login/Sign up</a>
<?php }?>
                          </div>
                        </div>
                      </div>

                    </form>
                </div>                
            </div>
        </div><!-- End of Card Container -->
    </div><!-- End of Announcement -->
</div>
</div>
<!-- <footer class="mt-5 position-absolute w-100">
  <div class="f-head d-flex p-4">
    <img height="65" src="./assets/imgs/logo2.png">
    <div class="ms-2 d-flex align-items-center justify-content-center">
      <p><b><?=$web_title;?></b></p>
    </div>
  </div>
  <div class="f-body container d-flex align-items-center justify-content-center">
    <div class="row">
      <div class="col-md-2">
        <img height="100" src="./assets/imgs/logo2.png">
      </div>
      <div class="col-md-3">
        <h6>Gov Links</h6>
        <ul>
        <?php 
foreach(get_all_footerz() as $fdata){
   $td = $fdata['gov'];
   $td2 = rtrim(str_replace(array('http://', 'https://'), '', $td),'/');
?>
          <li><a class="linkz-footer" href="<?=$td;?>"><?=$td2;?></a></li>
<?php }?>
        </ul>
      </div>
      <div class="col-md-3">
        <h6>Official Social media Account</h6>
        <ul>
        <?php 
foreach(get_all_footerz() as $fdata){
   $td = $fdata['social'];
   $td2 = rtrim(str_replace(array('http://', 'https://'), '', $td),'/');

?>
          <li><a class="linkz-footer" href="<?=$td;?>"><?=$td2;?></a></li>
<?php }?>
        </ul>
      </div>
      <div class="col-md-3">
        <h6>Contact Us</h6>
        <ul>
<?php 
foreach(get_all_footerz() as $fdata){
   $td = $fdata['contact'];
   $td2 = rtrim(str_replace(array('http://', 'https://'), '', $td),'/');

?>
          <li><a class="linkz-footer" href="<?=$td;?>"><?=$td2;?></a></li>
<?php }?>
        </ul>
      </div>
    </div>
  </div>
</footer> -->

<style>
body{
  background-color:rgba(238, 225, 180, 0.9);
}
nav{
    background-color:rgb(0,0,0,0.8);
}
.main-c{
    margin-top: 150px;
    height:100%;
}
.pm-box{
    /* background-color:rgba(238, 225, 180, 0.9); */
    background-color:white;
    border-radius:10px;
}
.pm-box-info p{
    margin:0;
}
  .cstm-img-box{
    border: 1px solid rgb(0,0,0,0.3);
    border-radius:10px;
    transition: .4s;
  }
  .cstm-img-box:hover{
    border: 1px solid rgb(0,0,0,0.1);
    box-shadow: 0px 5px 5px 1px rgb(0,0,0,0.5)!important;
    transition: .2s;
    transform: translateY(-5px);
  }

.cstm-card {
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 15px;
  overflow: hidden; 
}

.cstm-card-content {
padding: 15px;
}

.cstm-card-content p, .linkz-footer {
max-height: 100px; 
overflow: hidden;
text-overflow: ellipsis;
word-wrap: break-word;
line-height: 1.5em;
}
</style>

<link rel="stylesheet" type="text/css" href="./assets/style.css">
<script>

const BarangayClearance = "ICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Im10LTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+RnVsbCBOYW1lPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJmdWxsbmFtZSIgcGxhY2Vob2xkZXI9IkVudGVyIHlvdXIgbmFtZSIgdmFsdWU9IiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9InJvdyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC02IG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkRhdGUgb2YgQmlydGg8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJkb2IiIGlkPSJkb2IiIG9uY2hhbmdlPSJzZXRBZ2UoKTsiIG1heD0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC02IG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkFnZTwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHJlYWRvbmx5IHR5cGU9Im51bWJlciIgaWQ9ImFnZSIgbmFtZT0iYWdlIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBhZ2UiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlBsYWNlIG9mIEJpcnRoPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIG5hbWU9InBvYiIgcGxhY2Vob2xkZXI9IkVudGVyIHBsYWNlIG9mIGJpcnRoIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgYmFyYW5nYXkgY2xlYXJhbmNlPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9InB1cnBvc2UiIHBsYWNlaG9sZGVyPSJFbnRlciBQdXJwb3NlIj48L3RleHRhcmVhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const CertificateResidency = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTYgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+RGF0ZSBvZiBSZXNpZGVuY3k8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJkb3IiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgY2VydGlmaWNhdGUgcmVzaWRlbmN5PC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9InB1cnBvc2UiIHBsYWNlaG9sZGVyPSJFbnRlciBQdXJwb3NlIj48L3RleHRhcmVhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const CertificateIndigency = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgY2VydGlmaWNhdGUgaW5kaWdlbmN5PC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9InB1cnBvc2UiIHBsYWNlaG9sZGVyPSJFbnRlciBQdXJwb3NlIj48L3RleHRhcmVhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const OathOfUndertaking = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTYgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+RGF0ZSBvZiBSZXNpZGVuY3k8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJkb3IiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgZmlyc3QgdGltZSBzZWVrZXIgY2VydGlmaWNhdGlvbjwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx0ZXh0YXJlYSBjbGFzcz0iZm9ybS1jb250cm9sIiBuYW1lPSJwdXJwb3NlIiBwbGFjZWhvbGRlcj0iRW50ZXIgUHVycG9zZSI+PC90ZXh0YXJlYT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2Pg==";
const BusinessClearance = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5CdXNpbmVzcyBOYW1lPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJidXNpbmVzc05hbWUiIHBsYWNlaG9sZGVyPSJFbnRlciB5b3VyIGJ1c2luZXNzIG5hbWUiIHZhbHVlPSIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+QnVzaW5lc3MgQWRkcmVzczwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJidXNpbmVzc0FkZHJlc3MiIHBsYWNlaG9sZGVyPSJCbGRnIE5vLCBvciBCbG9jaywgTG90LCBQaGFzZSwgU3RyZWV0LCBTdWJkaXZpc2lvbiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Im10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5OYW1lIG9mIFRoZSBPd25lcjwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJvd25lck5hbWUiIHBsYWNlaG9sZGVyPSJPd25lcidzIE5hbWUiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNiBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+SXNzdWFuY2UgRGF0ZTwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJpc3N1YW5jZURhdGUiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const BarangayID = "PGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMgbXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlN1cm5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBhdXRvY29tcGxldGU9Im9mZiIgbmFtZT0ic3VybmFtZSIgcGxhY2Vob2xkZXI9IiIgdmFsdWU9IiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNCBtdC0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+Rmlyc3QgTmFtZTwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJmaXJzdE5hbWUiIHBsYWNlaG9sZGVyPSIiIHZhbHVlPSIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMgbXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPk1pZGRsZSBOYW1lPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9Im1pZGRsZU5hbWUiIHBsYWNlaG9sZGVyPSIiIHZhbHVlPSIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTIgbXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlN1ZmZpeDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJzdWZmaXgiIHBsYWNlaG9sZGVyPSIiIHZhbHVlPSIiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPCEtLSBTdGFydCBSb3cgLS0+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJyb3ciPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+Q2l2aWwgU3RhdHVzPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImZvcm0tY2hlY2siPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNoZWNrLWlucHV0IiB0eXBlPSJyYWRpbyIgbmFtZT0iY2l2aWxfc3RhdHVzIiBpZD0iY1NpbmdsZSIgdmFsdWU9IlNpbmdsZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWwgY2xhc3M9ImZvcm0tY2hlY2stbGFiZWwiIGZvcj0iY1NpbmdsZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFNpbmdsZQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJmb3JtLWNoZWNrIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jaGVjay1pbnB1dCIgdHlwZT0icmFkaW8iIG5hbWU9ImNpdmlsX3N0YXR1cyIgaWQ9ImNNYXJyaWVkIiB2YWx1ZT0iTWFycmllZCI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWwgY2xhc3M9ImZvcm0tY2hlY2stbGFiZWwiIGZvcj0iY01hcnJpZWQiPk1hcnJpZWQ8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImZvcm0tY2hlY2siPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNoZWNrLWlucHV0IiB0eXBlPSJyYWRpbyIgbmFtZT0iY2l2aWxfc3RhdHVzIiBpZD0iY1dpZG93ZWQiIHZhbHVlPSJXaWRvd2VkIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbCBjbGFzcz0iZm9ybS1jaGVjay1sYWJlbCIgZm9yPSJjV2lkb3dlZCI+V2lkb3dlZDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtMyBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5EYXRlIG9mIEJpcnRoPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0iZGF0ZSIgbmFtZT0iZG9iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC0zIG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkJsb29kIFR5cGU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJibG9vZFR5cGUiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+UmVsaWdpb248L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJyZWxpZ2lvbiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtMyBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5DZWxscGhvbmUgTnVtYmVyPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iY3BOdW1iZXIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KCiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwhLS0gRW5kIFJvdyAtLT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxocj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5FbWVyZ2VuY3kgQ29udGFjdDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJyb3ciPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNCBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJlbWVyZ2VuY3lOYW1lIiBwbGFjZWhvbGRlcj0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC00IG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkNlbGxwaG9uZSBOdW1iZXI8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJlbWVyZ2VuY3lDb250YWN0IiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC00IG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlJlbGF0aW9uc2hpcDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIG5hbWU9ImVtZXJnZW5jeVJlbGF0aW9uc2hpcCIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";

document.addEventListener("DOMContentLoaded", function() {
      const dobInput = document.getElementById("dob");
      if (dobInput) {
        dobInput.max = new Date().toISOString().split("T")[0];
      }

      window.setAge = function() {
        const dobInput = document.getElementById("dob");
        const ageInput = document.getElementById("age");

        if (!dobInput || !ageInput) return;

        const dob = dobInput.value;

        if (dob) {
          const dobDate = new Date(dob);
          const today = new Date();
          
          let years = today.getFullYear() - dobDate.getFullYear();
          const monthDifference = today.getMonth() - dobDate.getMonth();
          const dayDifference = today.getDate() - dobDate.getDate();

          if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
            years--;
          }

          ageInput.value = years;
        } else {
          ageInput.value = '';
        }
      }
});

function set_fs(){
  const fullname = "<?=$fullname;?>";
  const street = "<?=$street;?>";
  const inpName = document.querySelector('input[name="fullname"]');
  const inpAddr = document.querySelector('input[name="houseAddress"]');

  if(inpName && fullname.trim() != ""){
    inpName.value = fullname;
  }
  if(inpAddr && street.trim() != ""){
    inpAddr.value = street;
  }
  
}

function change_view() {
  var dropdown = document.getElementById("service_selc");
  var selected_view = parseInt(dropdown.options[dropdown.selectedIndex].value);
  var service_view = document.getElementById("service_view");

  if(selected_view === 0){
    service_view.innerHTML = atob(BarangayClearance);
  }
  else if(selected_view === 1){
    service_view.innerHTML = atob(CertificateResidency);
  }
  else if(selected_view === 2){
    service_view.innerHTML = atob(CertificateIndigency);
  }
  else if(selected_view === 3){
    service_view.innerHTML = atob(OathOfUndertaking);
  }
  else if(selected_view === 4){
    service_view.innerHTML = atob(BusinessClearance);
  }
  else if(selected_view === 5){
    service_view.innerHTML = atob(BarangayID);
  }

  // Set fulname/street
  set_fs();
  <?php if($isLogged === False || $isVerified === False){?>

var inputFields = document.querySelectorAll('input, textarea');
inputFields.forEach(function(input) {
    input.setAttribute('readonly', true);
});

<?php }?>

}

document.getElementById("service_view").innerHTML = atob(BarangayClearance);
set_fs();

<?php if($isLogged === False || $isVerified === False){?>

var inputFields = document.querySelectorAll('input, textarea');
inputFields.forEach(function(input) {
    input.setAttribute('readonly', true);
});

<?php }?>

</script>

