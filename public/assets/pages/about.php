<?php $default->nav();?>

    <style>
        :root{
            --cover-op: 0.7;
        }
        .about-page {
            background: linear-gradient( rgba(0,0,0, var(--cover-op)), rgba(0, 0, 0, var(--cover-op)) ), url('./assets/imgs/bg-img1.png');
            background-size: cover;
            background-position:center;
            background-repeat: no-repeat;
            min-height: 100vh;
            width:100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .about-page h1 {
            font-size: 3rem;
            color: white;
        }
        .about-page p {
            font-size: 1.2rem;
            color: white;
        }
        @media (max-width: 767.98px) {
            .about-page h1 {
                font-size: 2.5rem;
            }
            .about-page p {
                font-size: 1rem;
            }
        }
    </style>
    <div class="about-page">
        <div class="container">
            <div class="row">
                <div class="col-md-4 d-flex justify-content-center">
                    <img height="200" src="<?=$web_logo;?>">
                </div>
                <div class="col-md-7">
                    <h1 class="mb-4"><?=$web_data['about_title'];?></h1>
                    <p>
                      <?=$web_data['about_desc'];?>
                    </p>
                </div>
            </div>
        </div>
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

