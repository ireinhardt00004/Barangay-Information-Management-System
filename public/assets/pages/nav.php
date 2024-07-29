
<nav id="navbar" class="navbar fixed-top navbar-dark navbar-expand-lg justify-content-center align-items-center">
  <div class="container">
    <a class="navbar-brand" id="cstm-nav-brand" style="font-size:14px" href="home">
        <img height="60" src="<?=$this->web_logo?>">
        <?=$this->web_title?>
    </a>
    <button class="navbar-toggler nav-m-btn navbar-light shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navdrop" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navdrop">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item mx-1">
          <a class="nav-link" id="home" href="home">
            HOME
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" id="about" href="about">
            ABOUT
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" id="announcements" href="announcements">
            ANNOUNCEMENTS
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" id="service" href="service">
            SERVICE
          </a>
        </li>
        <li class="nav-item mx-1">
          <a class="nav-link" id="programs" href="programs">
            PROGRAMS
          </a>
        </li>
<?php
$navs_data = get_all_navs();
$last_data = end($navs_data);
foreach($navs_data as $data){
    $nav = $data['nav_name'];
    $page_id = intval($data['page_id']);
    $nav_id = $data['id'];
    $page = base64_decode(data_extract($page_id, 'id', get_all_pages())['page_name']);
?>
        <li class="nav-item mx-1">
          <a class="nav-link" href="<?=$page;?>">
            <?=$nav;?>
          </a>
        </li>
        
<?php
}
$em_contact = json_decode($this->web_data["em_contacts"]);
$em_city_hall = base64_decode($em_contact->city_hall);
$em_police = base64_decode($em_contact->police);

?>
      </ul>
      <div class="text-white">
        <p class="m-0"><span>Barangay Hall #:</span> <?=$em_city_hall;?></p>
        <p class="m-0"><span>Police Station #:</span> <?=$em_police;?></p>
      </div>
    </div>
  </div>
  <script>
const navLinks = document.querySelectorAll('.nav-link');

const uri = window.location.href;
const uriParts = uri.split('/');
const uriValue = uriParts[uriParts.length - 1];
navLinks.forEach(link => {
   const href = link.getAttribute('href');
   const hrefParts = href.split('=');
   const linkValue = hrefParts[hrefParts.length - 1];
   if (linkValue === uriValue) {
      link.classList.add('active-nav2');
   }
});
  </script>
  <style>
@media (max-width: 768px) {
   .carousel-content h1{
      font-size: 1.5rem!important;
   }
   .navbar{
      background-color: #202020!important;
      /* position: static!important; */
  }
  .navbar-nav{
      text-align:center; 
      display:flex!important;
  }
  .nav-item{
      padding: 5px!important;
  }
  .nav-item a{
      padding: 5px!important;
  }
  header{
   /* margin-top:3rem; */
  }
}

  </style>
</nav>
