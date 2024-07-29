<?php
$default->nav();
include('./assets/pages/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

if(!isset($_GET['id']) || empty($_GET['id'])){
    echo "<script>window.location.href='programs';</script>";
}

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = $conn->prepare("SELECT * FROM `programs` WHERE id=:id;");
    $sql->bindParam(":id", $id);
    $sql->execute();
    $prog_data = $sql->fetchAll(PDO::FETCH_ASSOC)[0];

    foreach($prog_data as $k => $v){
        $prog_data[$k] = base64_decode($prog_data[$k]);
    }

    $p_img = $prog_data['cover'];
    $p_title = $prog_data['title'];
    $p_content = $prog_data['content'];
}

?>
<div class="container d-flex align-items-center justify-content-center mb-3">
    <div class="ms-2 mt-5 col-md-4 w-100 h-100"> <!-- Start of annoucement -->
        <h2 class="mt-5"><a href="programs"><i class="fa-solid fa-left-long"></i> Go back</a></h2>
        <div class="row p-1 rounded"><!-- Start of Card Container -->
            <div class="col-md-4 p-2">
                <img draggable="false" class="img-fluid shadow cstm-img-box" src="./assets/imgs_uploads/<?=$p_img;?>">
            </div>
            <div class="col">
                <h1><b><?=$p_title;?></b></h1>
                <?=$Parsedown->text($p_content);?>
            </div>
        </div><!-- End of Card Container -->
    </div><!-- End of Announcement -->
</div>

<footer class="mt-5">
  <div class="f-head d-flex p-4">
    <img height="65" src="<?=$web_logo;?>">
    <div class="ms-2 d-flex align-items-center justify-content-center">
      <p><b><?=$web_title;?></b></p>
    </div>
  </div>
  <div class="f-body container d-flex align-items-center justify-content-center">
    <div class="row">
      <div class="col-md-2">
        <img height="100" src="<?=$web_logo;?>">
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
</footer>

<style>
nav{
    background-color:rgb(0,0,0,0.8);
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
</style>

<link rel="stylesheet" type="text/css" href="./assets/style.css">
