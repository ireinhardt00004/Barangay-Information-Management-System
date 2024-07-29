<?php
$default->nav();

$isLogged = False;
$isVerified = False;
$uuid = $_SESSION["sess_uid"];

if(isset($_SESSION["logged"])){
  if($_SESSION["logged"] === True){
    $isLogged = True;
  }

  $status = data_extract($uuid, "uid", get_all_data(2))["verified"];
  if($status === "true"){
    $isVerified = True;
  }
}

$_SESSION['alert'] = '';
function rcode() {
  $code = '';
  $characters = '013456789ABCDELMNOPSTU';

  for ($i = 0; $i < 2; $i++) {
      for ($j = 0; $j < 5; $j++) {
          $code .= $characters[rand(0, strlen($characters) - 1)];
      }
      if ($i !== 1) {
          $code .= '-';
      }
  }

  return $code;
}

$request_uid = rcode();
if(isset($_POST['report_request'])){
  if($isVerified === False){
    return;
  }

  $fullname = si($_POST['fullname']);
  $email = si($_POST['email']);
  $issue = si($_POST['issue']);

  $sql = $conn->prepare("INSERT INTO `report_request`(uid, fullname, email, issue) VALUES(:uid, :fn, :em, :is);");
  $sql->bindParam(":uid", $request_uid);
  $sql->bindParam(":fn", $fullname);
  $sql->bindParam(":em", $email);
  $sql->bindParam(":is", $issue);
  try {
    $result = $sql->execute();
    if($result){
      $_SESSION['alert'] = "toast('Success!','2000','rgb(0,255,0,0.5)')";
    }else{
      $_SESSION['alert'] = "toast('Failed!','2000','rgb(200,0,0,0.5)')";
    }
  } catch (PDOException $e) {
    $_SESSION['alert'] = "toast('Error! Something went wrong!','2000','rgb(255,0,0,0.5)')";
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
<div class="semi-body d-flex align-items-center">
  <div class="container w-100 bg-white p-3 rounded shadow">
    <h2>Report request</h2><br>
    <form method="POST">
    <div class="row d-flex align-items-center justify-content-center">
      <div class="col-md-5">
        <div class="input-group">
            <label class="input-group-text">Fullname</label>
            <input class="form-control" name="fullname" type="text" placeholder="" required>
        </div>
      </div>

      <div class="col-md-5">
        <div class="input-group">
            <label class="input-group-text">Email</label>
            <input class="form-control" name="email" type="email" placeholder="" required>
        </div>
      </div>
      <div class="mt-2 col-md-10">
          <textarea style="height:300px;" class="form-control" name="issue" required placeholder="Enter issue.."></textarea>
      </div>
      <div class="col-md-10">
        <!-- <div class="bg-dark"> -->
<?php if($isVerified){?>
          <input class="mt-2 btn btn-success" type="submit" name="report_request" value="Submit">
<?php }elseif($isLogged){?>
          <small class="text-danger">Account must be verified to use this service*</small><br>
          <a href="/member&v=verify" class="btn btn-primary">Get verified</a>
<?php }else{?>
          <small class="text-danger">You must login and have a verifed account to use this service*</small><br>
          <a href="/login" class="btn btn-outline-warning">Login/Sign up</a>
<?php }?>
      <!-- </div> -->
      </div>
    </div>
  </form>
  </div>
</div>

<style>
:root{
  --cover-op: 0.8;
}
body{
  background-color:rgba(238, 225, 180, 0.9);
}
nav{
    background-color:rgb(0,0,0,0.8);
}
.semi-body{
  height:100%;
}
.card{
  user-select:none;
  transition: .6s;
  color:white;
  overflow: hidden;
  padding:15px;
  border-radius:10px;
  border: 1px solid white;
  box-shadow: 0px 3px 2px 1px rgb(0,0,0,0.1);
  background: linear-gradient( rgba(0,0,0, var(--cover-op)), rgba(0,0,0, var(--cover-op)) ), url('./assets/imgs/logo.png');
  background-size: cover;
  background-position: center;
}
.card:hover{
  color:white;
  transition:.3s;
  transform: translateY(-15px);
  box-shadow: 0px 5px 10px 2px rgb(0,0,0,0.5);
}
.card:active{
  transition:.1s;
  color: rgba(239,227,187,1);
  transform: scale(1.1);
  box-shadow:none;
}
</style>
<script>
const navElement = document.getElementById('navbar');
navElement.classList.remove('fixed-top');
navElement.classList.add('sticky-top');

</script>
<link rel="stylesheet" type="text/css" href="./assets/style.css">
<script>
<?php if($isLogged === False || $isVerified === False){?>

var inputFields = document.querySelectorAll('input, textarea');
inputFields.forEach(function(input) {
    input.setAttribute('readonly', true);
});

<?php }?>
</script>
