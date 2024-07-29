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
        <h2 class="mt-5">Announcements</h2>
        <div class="row p-1 border rounded"><!-- Start of Card Container -->
<?php
$sql = $conn->prepare("SELECT * FROM `announcement` ORDER BY id DESC");
$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $a_data){
   $a_img = base64_decode($a_data['cover']);
   $a_title = base64_decode($a_data['title']);
   $a_content = base64_decode($a_data['content']);
   $creation_date = $a_data['date_created'];

?>
            <div class="col-md-4 mt-3">
                <div class="shadow border rounded p-1">
                    <i><img class="img-fluid" src="./assets/imgs_uploads/<?=$a_img;?>" alt="#"/></i>
                    <h3><?=$a_title;?></h3>
                    <p style="font-size:13px;" class="m-0 p-0"><i><b>Date:</b> <?=$creation_date;?></i></p>
                    <div class="" style="text-align:justify;">
                    <p><?=$Parsedown->text($a_content);?></p>
                    </div>
                </div>
            </div>
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
