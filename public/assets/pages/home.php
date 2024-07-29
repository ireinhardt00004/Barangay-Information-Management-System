<?php
$default->nav();
include('./assets/pages/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
?>
<header class="w-100 mb-4" style="overflow: hidden; max-height: 100%;">
   <div class="carousel-container">
      <div id="headerCarousel" class="carousel slide" data-bs-ride="carousel">
         <div class="carousel-inner">
         <?php
$tmp_bool = True;
foreach(get_all_home_bg() as $bg){
   $isActive = '';
   if($tmp_bool){
      $tmp_bool = False;
      $isActive = 'active';
   }
   $bg_img = base64_decode($bg['name']);
?>
            <div class="carousel-item <?=$isActive;?>">
               <img src="./assets/imgs_uploads/<?=$bg_img;?>" class="d-block w-100 img-header-cover">
            </div>
<?php }?>
         </div>
      </div>
      <div class="cc-b carousel-control-prev">
         <a class="carousel-btn-main carousel-control-prev" href="#headerCarousel" role="button" data-bs-slide="prev">
         <span class="rounded-circle cstm-carousel-btn material-symbols-outlined">arrow_back_ios_new</span>
         </a>
      </div>

      <div class="cc-b carousel-control-next">
         <a class="carousel-btn-main carousel-control-next" href="#headerCarousel" role="button" data-bs-slide="next">
         <span class="rounded-circle cstm-carousel-btn material-symbols-outlined">arrow_forward_ios</span>
         </a>
      </div>
      <div class="carousel-content">
         <h1><span style="color: rgb(248, 220, 120);"><?=$web_data['head_title'];?></span></h1>
<?php 
foreach(get_all_headersBtns() as $h_btn){
   $name = $h_btn['name'];
   $link = $h_btn['link'];
   $outline = ($h_btn['outline'] == 'off') ? 'cstm-btn-c-yellow' : '';
?>
         <a href="<?=$link;?>" class="rounded-pill btn cstm-btn1 <?=$outline;?>"><?=$name;?></a>
<?php }?>
      </div>
   </div>
</header>
<div class="container">
   <div class="row d-flex justify-content-center">
   <?php
foreach(get_all_cards() as $cdata){
   $img = base64_decode($cdata['img']);
   $ctitle = $cdata['title'];
   $clink = $cdata['link'];
?>
      <div class="col-md-4">
         <div class="three-box">
            <i><img class="img-fluid" style="max-height: 150px;" src="./assets/imgs_uploads/<?=$img;?>" alt="<?=$img;?>"/></i>
            <h3><?=$ctitle;?></h3>
            <a class="mt-2 rounded-pill btn cstm-btn2 cstm-btn-c-yellow" href="<?=$clink;?>">Learn More</a>
         </div>
      </div>
<?php }?>
   </div>
   <!-- Start of annoucement -->
   <div class="ms-2 mt-5 col-md-4 w-100">
    <div class="p-1 home-anoucement">
      <h3 class="ms-2"><b>Recent</b> Announcement</h3>
    </div>
    <p class="ms-3">Check the latest news, events and announcements here.</p>
   <!-- Start of Card Container -->
   <div class="row d-flex justify-content-center">
<?php
$sql = $conn->prepare("SELECT * FROM `announcement` ORDER BY id DESC LIMIT 3;");
$sql->execute();

$result = $sql->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $a_data){
   $a_img = base64_decode($a_data['cover']);
   $a_title = base64_decode($a_data['title']);
   $a_content = base64_decode($a_data['content']);

?>

      <div class="col-md-4">
         <div class="">
            <i><img class="img-fluid" src="./assets/imgs_uploads/<?=$a_img;?>" alt="#"/></i>
            <h3><?=$a_title;?></h3>
            <div class="" style="text-align:justify;">
               <p><?=$Parsedown->text($a_content);?></p>
            </div>
         </div>
      </div>
<?php }?>
      
   </div> 
  <!-- End of Card Container -->
  </div>
  <div class="d-flex justify-content-center mt-4">
    <a class="cstm-btn3 btn" href="/announcements">View all Announcement</a>
  </div>
  <div class="map-container d-flex justify-content-center mt-3">
    <div class="mapouter w-100">
      <div class="gmap_canvas">
      <iframe width="100%" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3865.673286431573!2d120.83200192202257!3d14.330400612984032!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33962b315248f85f%3A0x27b10b5030b4d8e1!2sBrgy.%20Tres%20Cruses%20(Barangay%20Hall)!5e0!3m2!1sen!2sph!4v1698968047190!5m2!1sen!2sph" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </iframe>
      </div>
    </div>
  </div>
  <!-- End of Announcement -->
  
  <!-- rgba(10,59,86,255) -->
</div>
<footer>
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
<?php
$theme = json_decode($web_data["theme"], true);
$p_c = base64_decode($theme["primary"]);
$b_c = base64_decode($theme["bg"]);
$t_c = base64_decode($theme["text"]);
?>
   :root{
    /* --primary-color: #101010; */
    /* #f8dc78 */
    --primary-color: <?=$p_c;?>;
    --text-color: <?=$t_c;?>;
    --background: <?=$b_c;?>;
   }
   body{
      color:var(--text-color);
      background-color: var(--background);
   }
   .home-anoucement{
      border-left: 4px solid var(--primary-color);
   }
   .three-box{
      background: linear-gradient(to top, #FFFFFF1A 50%, var(--primary-color) 50%);
   }
  .cstm-card {
   /* rgb(255, 225, 116); */
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 15px;
    overflow: hidden; 
  }
  .cstm-btn1{
   color: var(--background);
  }
  .cstm-btn2{
   color:var(--text-color)!important;
  }
  .cstm-btn3{
   color: var(--background );
   background-color: var(--primary-color);
  }
  
  .cstm-btn-c-yellow{
   background-color: var(--primary-color)!important;
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

  .cstm-carousel-btn{
    transform: scale(1.3);
    padding:2px;
    text-align: center;
    display: flex;
    justify-content: center;
    color: var(--primary-color)!important;
    border: 3px solid var(--primary-color)!important;
   }
   @media (max-width: 768px) {
   .carousel-content h1{
      font-size: 1.5rem!important;
   }
   .navbar{
      background-color: #202020!important;
  }
}

.cstm-po-static{
   position: static!important;
}
.sticky {
   transition: 1s;
  position: fixed!important;
  top: 0;
  width: 100%
}

.sticky + .content {
  padding-top: 3px;
}
</style>
<link rel="stylesheet" type="text/css" href="./assets/style.css">
<script>
  function checkScroll() {
    var header = document.getElementById('navbar');
    var sticky = header.offsetTop;
    
    function snav() {
      if (window.pageYOffset > sticky) {
        header.classList.add('sticky');
      } else {
        header.classList.remove('sticky');
      }
    }

    // Initial check
    if (window.innerWidth <= 768) {
      window.onscroll = snav;
      header.classList.add('cstm-po-static');
    } else {
      header.classList.add('fixed-top');
      header.classList.remove('cstm-po-static');
      header.classList.remove('sticky');
    }
  }

  // Initial check on page load
  checkScroll();

  // Check on window resize
  window.onresize = function() {
    checkScroll();
  };
</script>
