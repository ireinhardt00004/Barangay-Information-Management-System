<?php

if(isset($_GET['id']) && !empty(trim($_GET['id']))){
    $id = intval($_GET['id']);
    $data = data_extract($id, 'id', get_all_report_request());
    if($data == false){
      echo "<script>window.location.href='/staff&v=reports';</script>";
      exit;
    }
    $fn = $data['fullname'];
    $em = $data['email'];
    $is = $data['issue'];
}

?>

<button class="btn btn-outline-warning" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i>Go Back</button>
<!-- <h2 class="mt-2 ">Viewing a report request</h2> -->
<div class="mt-2 semi-body d-flex align-items-center">
  <div class="text-center container w-100 bg-white p-3 rounded shadow">
    <form method="POST">
    <div class="row d-flex align-items-center justify-content-center">
      <div class="col-md-5">
        <div class="input-group">
            <label class="input-group-text">Fullname</label>
            <input class="form-control cbtn" readonly value="<?=$fn;?>" type="text" placeholder="" required>
        </div>
      </div>

      <div class="col-md-5">
        <div class="input-group">
            <label class="input-group-text">Email</label>
            <input class="form-control cbtn" readonly value="<?=$em;?>" type="email" placeholder="" required>
        </div>
      </div>
      <div class="mt-2 col-md-10">
          <textarea style="height:300px;" readonly class="cbtn form-control" required ><?=$is;?></textarea>
      </div>
    </div>
  </div>
</div>

<style>
.cbtn{
    background-color:white!important;
}
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
<link rel="stylesheet" type="text/css" href="./assets/style.css">
