<?php
$default->nav();
include('./assets/pages/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
?>
<div class="semi-body d-flex align-items-center ">
  <div class="container w-100">
    <div class="row d-flex align-items-center justify-content-center p-4">
      <a href="/make_request" class="text-center text-decoration-none mx-auto col-md-3 mt-4 card">
        <i style="font-size:100px;" class="fa-solid fa-file-import"></i>
        <h3 class="decoration-none">Make request</h3>
      </a>
      <a href="/track_request" class="text-center text-decoration-none mx-auto col-md-3 mt-4 card">
        <i style="font-size:100px;" class="fa-solid fa-magnifying-glass-location"></i>
        <h3 class="decoration-none">Track request</h3>
      </a>
      <a href="/report_request" class="text-center text-decoration-none mx-auto col-md-3 mt-4 card">
        <i style="font-size:100px;" class="fa-solid fa-bug"></i>
        <h3 class="decoration-none">Report something</h3>
      </a>
    </div>
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
  height:100vh;
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
