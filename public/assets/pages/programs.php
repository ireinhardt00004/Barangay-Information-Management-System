<?php
$default->nav();
include('./assets/pages/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);

$a_img = "bg-img2.jpg";
$a_title = "My announcement";
$a_content = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae voluptates delectus voluptatibus officia dolor assumenda at ab obcaecati, culpa, quibusdam corrupti ducimus. Doloribus placeat voluptatum quam accusamus quidem aliquid cum.";
?>
<div class="container d-flex align-items-center justify-content-center mb-3">
    <div class="ms-2 mt-5 col-md-4 w-100 h-100"> <!-- Start of annoucement -->
        <h2 class="mt-5">Programs</h2>
        <div class="row p-1 border rounded" style="z-index:1; background-color:rgb(0,0,0,0.1);"><!-- Start of Card Container -->
        <p class="m-0" style="color:rgb(0,0,0,0.4);">Click items to view content..</p>
<?php
$sql = $conn->prepare("SELECT * FROM `programs` ORDER BY id DESC;");
$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $a_data){
    $a_id = $a_data['id'];
    $a_img = base64_decode($a_data['cover']);
    $a_title = base64_decode($a_data['title']);
    $a_content = base64_decode($a_data['content']);
    $creation_date = $a_data['date_created'];

?>
            <a class="col-md-4 mt-3 text-decoration-none" href="view_program&id=<?=$a_id;?>">
                <div class="shadow program-b-wrap">
                    <div class="program-box d-flex align-items-center justify-content-center border rounded p-1" style="background: linear-gradient( rgba(0,0,0, 0.5), rgba(0,0,0, 0.6)), url('./assets/imgs_uploads/<?=$a_img;?>');">
                        <div>
                            <h3 class="text-center"><?=$a_title;?></h3>
                        </div>
                    </div>
                    <p style="font-size:13px;" class="mx-1 p-0 text-muted"><i><b>Date Posted:</b> <?=$creation_date;?></i></p>
                </div>
            </a>
<?php }?>
        </div><!-- End of Card Container -->
    </div><!-- End of Announcement -->
</div>

<footer class="">
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
    /* background: linear-gradient( rgba(0,0,0, 0.9), rgba(0,0,0, 0.9)), url('../assets/imgs/bg-img1.png'); */
.program-b-wrap{
    /* overflow: hidden; */
    transition: .4s;
    border-radius:10px;
    z-index: 2;
}
.program-b-wrap:hover{
    transition: .2s;
    transform: translateY(-10px);
    box-shadow: 0px 2px 5px 1px rgb(0,0,0,0.6)!important;
}
.program-box{
    transition: .4s;
    color: rgba(238, 225, 180, 1);
    height:230px;
    width:100%;
    background-size: cover!important;
    background-position: center!important;
}
.program-box:hover{
    transition: .2s;
    transform: translateY(-10px);
}
nav{
    background-color:rgb(0,0,0,0.8);
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
