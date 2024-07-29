<?php

if(isset($_POST['new_imgs'])){
    $img = upload_file('./assets/imgs_uploads/',$_FILES);
    if(empty($img)){
        $img = 'bg-img1.png';
    }
    $img = base64_encode($img);
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `home_imgs`(name,img_path) VALUES(:name,:name);");
    $sql->bindParam(":name",$img);
    $sql->execute();
    act_logger("Added new home header background", $current_uri);
    echo "<script>window.location.href='$c_role&v=cms_general'</script>";
}

if(isset($_POST['new_logo'])){
    $tmpFilePath = $_FILES['imageFilez']['tmp_name'];
    // if ($check !== false) {
    $orig_name = $_FILES['imageFilez']['name'];
    $ext = end(explode(".",$orig_name));
    $file_name = 'new_logo_'.enc('logo:'.$orig_name).'.'.$ext;
    $targetFilePath = './assets/imgs/'.$file_name;
    if(!file_exists($targetFilePath)){
        move_uploaded_file($tmpFilePath, $targetFilePath);
    }
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("UPDATE `general_conf` set logo=:logo WHERE id=1;");
    $sql->bindParam(":logo",$file_name);
    $sql->execute();
    act_logger("Changed website logo ", $current_uri);

}


if(isset($_POST['delete_img'])){
    $item_id = $_POST['item_id'];

    $img_del_name = '';
    foreach(get_all_home_bg() as $datah){
        if($datah['id'] == $item_id){
            $img_del_name = $datah['name'];
            break;
        }
    }

    if(base64_decode($img_del_name) != "bg-img1.png"){
        unlink('./assets/imgs_uploads/'.base64_decode($img_del_name));
    }

    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("DELETE FROM `home_imgs` WHERE id=:im_id;");
    $sql->bindParam(":im_id",$item_id);
    $sql->execute();
    act_logger("Removed a home header image | ID: $id", $current_uri);
}

if(isset($_POST['create_h_btn'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }
    $outline = 'off';
    if(isset($_POST['btn_outline'])){
        $outline = $_POST['btn_outline'];
    }
    $name = $_POST['btn_name'];
    $link = $_POST['btn_url'];
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `header_btns`(name, link, outline) VALUES(:name,:link,:outline);");
    $sql->bindParam(":name",$name);
    $sql->bindParam(":link",$link);
    $sql->bindParam(":outline",$outline);
    $sql->execute();
    act_logger("Created a home header button with name '$name' ", $current_uri);
}


if(isset($_POST['edit_head_btn'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }
    $outline = 'off';
    if(isset($_POST['btn_outline'])){
        $outline = $_POST['btn_outline'];
    }
    $id = $_POST['btn_id'];
    $name = $_POST['btn_name'];
    $link = $_POST['btn_url'];
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("UPDATE `header_btns` SET name=:name, link=:link, outline=:outline WHERE id=:id;");
    $sql->bindParam(":id",$id);
    $sql->bindParam(":name",$name);
    $sql->bindParam(":link",$link);
    $sql->bindParam(":outline",$outline);
    $sql->execute();
    act_logger("Edited a home header button with ID: '$id' ", $current_uri);

}

if(isset($_POST['add_linkz'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }

    $gov = $_POST['link_gov'];
    $social = $_POST['link_social'];
    $contact = $_POST['link_contact'];

    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `footerz`(gov, social, contact) VALUES(:gov,:social,:contact);");
    $sql->bindParam(":gov",$gov);
    $sql->bindParam(":social",$social);
    $sql->bindParam(":contact",$contact);
    $sql->execute();
    act_logger("Added footer links ", $current_uri);

}

if(isset($_POST['add_rtype'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }

    $new_type = $_POST['new_rtype'];

    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `srvs_rtypes`(request_type) VALUES(:rt);");
    $sql->bindParam(":rt",$new_type);
    $sql->execute();
    act_logger("Created new service request type '$new_type' ", $current_uri);
}

if(isset($_POST['edit_linkz'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }

    $id = $_POST['id'];
    $gov = $_POST['link_gov'];
    $social = $_POST['link_social'];
    $contact = $_POST['link_contact'];

    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("UPDATE `footerz` set gov=:gov, social=:social, contact=:contact WHERE id=:id;");
    $sql->bindParam(":id",$id);
    $sql->bindParam(":gov",$gov);
    $sql->bindParam(":social",$social);
    $sql->bindParam(":contact",$contact);
    $sql->execute();
    act_logger("Edited footer links with ID '$id' ", $current_uri);

}

if(isset($_POST['save_general_changes'])){
    foreach($_POST as $key => $value){
        if($key !='head_title'){
            $_POST[$key] = si($_POST[$key]);
        }
    }
    $z_max_req = $_POST['service_max_no'];
    $ztitle = $_POST['web_title'];
    $zmeta_desc = $_POST['meta_desc'];
    $zhead_title = $_POST['head_title'];
    $zabout_title = $_POST['about_title'];
    $zabout_desc = $_POST['about_description'];
    $zpayment = $_POST['payment_amt'];
    $zgcash = $_POST['gcash_no'];
    $zpolice = base64_encode($_POST['polsta_no']);
    $zcthall = base64_encode($_POST['ciha_no']);
    $z_em_contacts = '{"city_hall":"'.$zcthall.'", "police":"'.$zpolice.'"}';

    $conn = $GLOBALS['conn'];
    if($_SESSION['sess_user_type'] === 0 ){
        $zprimary_color = base64_encode($_POST['primary_color']);
        $zbg_color = base64_encode($_POST['bg_color']);
        $ztext_color = base64_encode($_POST['text_color']);
        $ztheme = '{"primary":"'.$zprimary_color.'", "bg":"'.$zbg_color.'", "text":"'.$ztext_color.'"}';
        $sql = $conn->prepare("UPDATE `general_conf` set title=:ztitle, meta_desc=:meta_desc, head_title=:head_title, about_title=:about_title, about_desc=:about_desc, gcash_no=:gc, payment_amt=:pm, em_contacts=:em_contacts, theme=:ztheme, max_requests=:max_req WHERE id=1;");
        $sql->bindParam(":ztheme",$ztheme);
    }else{
        $sql = $conn->prepare("UPDATE `general_conf` set title=:ztitle, meta_desc=:meta_desc, head_title=:head_title, about_title=:about_title, about_desc=:about_desc, gcash_no=:gc, payment_amt=:pm, em_contacts=:em_contacts, max_requests=:max_req WHERE id=1;");
    }
    $sql->bindParam(":ztitle",$ztitle);
    $sql->bindParam(":meta_desc",$zmeta_desc);
    $sql->bindParam(":head_title",$zhead_title);
    $sql->bindParam(":about_title",$zabout_title);
    $sql->bindParam(":about_desc",$zabout_desc);
    $sql->bindParam(":pm",$zpayment);
    $sql->bindParam(":gc",$zgcash);
    $sql->bindParam(":em_contacts",$z_em_contacts);
    $sql->bindParam(":max_req", $z_max_req);
    $sql->execute();
    act_logger("Updated General data in Content Management ", $current_uri);
    exit;
}

if(isset($_POST['new_card'])){
    $img = upload_file('./assets/imgs_uploads/',$_FILES);
    if(empty($img)){
        $img = 'img1.png';
    }
    $img = base64_encode($img);
    $title = si($_POST['title']);
    $link = si($_POST['link']);


    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `home_cards`(img, title, link) VALUES(:img, :title,:link);");
    $sql->bindParam(":img",$img);
    $sql->bindParam(":title",$title);
    $sql->bindParam(":link",$link);
    $sql->execute();
    act_logger("Created home card with title '$title' ", $current_uri);

    echo "<script>window.location.href='$c_role&v=cms_general'</script>";
}

$em_contact = json_decode($web_data["em_contacts"]);
$em_city_hall = base64_decode($em_contact->city_hall);
$em_police = base64_decode($em_contact->police);
$max_req = $web_data["max_requests"];

?>

    <div class="w-100 shadow p-2 rounded mb-5"  style="background-color:rgb(255,255,255,0.4);">
<?php
if($_SESSION['sess_user_type'] === 0 ){
    $theme = json_decode($web_data["theme"], true);
    $p_c = base64_decode($theme["primary"]);
    $b_c = base64_decode($theme["bg"]);
    $t_c = base64_decode($theme["text"]);
?>
        <div class="mt-2">
            <h3>Landing page theme</h3>
            <hr>
        </div>
        <div class="mb-3 container row">

            <div class="col-4 mt-2">
                <div class="justify-content-center">
                    <label class="">Primary:</label>
                    <input type="color" class="form-control form-control-color" value="<?=$p_c?>" name="primary_color" title="Choose a color">
                </div>
            </div>
            <div class="col-4 mt-2">
                <div class="justify-content-center">
                    <label class="">Background:</label>
                    <input type="color" class="form-control form-control-color" value="<?=$b_c?>" name="bg_color" title="Choose a color">
                </div>
            </div>
            <div class="col-4 mt-2">
                <div class="justify-content-center">
                    <label class="">Texts:</label>
                    <input type="color" class="form-control form-control-color" value="<?=$t_c?>" name="text_color" title="Choose a color">
                </div>
            </div>
        </div>
        <div class="mt-2">
            <h3>Emergency Contact</h3>
            <hr>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="">
                    <label class="">City Hall #:</label>
                    <input class="form-control" autocomplete="off" name="ciha_no" value="<?=$em_city_hall;?>" placeholder="Ex: 09123123123">
                </div>
            </div>
            <div class="col-6">
                <div class="">
                    <label class="">Police Station #</label>
                    <input class="form-control" autocomplete="off" name="polsta_no" value="<?=$em_police;?>" placeholder="Ex: 09123123123">
                </div>
            </div>
        </div>
<?php }?>
        <!-- <div class="mt-2">
            <h3>Config</h3>
            <hr>
        </div>
        <div class="row">
            <div class="row">
                <div class="col-md-2">
                    <label>Max service request per Member </label>
                    <input class="form-control" type="number" value="<?=$max_req;?>" name="service_max_no">
                </div>
            </div>
        </div> -->
        <div class="mt-2">
            <h3>General</h3>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-2">
                <label>Logo:</label>
                <a class="btn btn-outline-primary" onclick="new_logo();">Change Logo</a>
            </div>
            <div class="mx-1 col-md-4">
                <label>Title:</label>
                <input class="form-control" autocomplete="off" name="web_title" value="<?=$web_title;?>" placeholder="example..">
            </div>
            <div class="col-md-4">
                <label>Meta description:</label>
                <textarea class="form-control" autocomplete="off" name="meta_desc" placeholder="example.."><?=$web_desc;?></textarea>
            </div>
        </dvi>
        <div class="mt-3">
            <h3>Home page</h3>
            <hr>
        </div>
        <div class="row">
            <div class="mx-1 col-md-6">
                <label>Head title:</label>
                <input class="form-control" autocomplete="off" name="head_title" value="<?=si($web_data['head_title']);?>" placeholder="example nav..">
            </div>
            <div class="border mt-1 form-wrap p-2">
                <h4>Home header background</h4>
        <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="new_image();">
            <i class=" rounded-circle fa-regular fa-plus"></i> Add image
        </button>

        <div class="table-wrap container d-lg-flex d-md-flex mt-3">
            <div class="row w-100">
<?php
foreach(get_all_home_bg() as $data){
    $img = base64_decode($data['img_path']);
    $item_id = ($data['id']);
?>
                <section class="m-2 image col-md-4 position-relative d-flex">
                    <button class="position-absolute badge rounded w-25 h-25 btn btn-danger" onclick="del_imgz('<?=$item_id;?>');"><i class="fa-solid fa-trash-can"></i></button>
                    <img class="w-100" src="./assets/imgs_uploads/<?=$img;?>">
                </section>
<?php }?>
            </div>
        </div>
    </div>
            <div class="container mt-2 border">
            <h4>Home header buttons</h4>
                <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="create_head_btn();">Add new button</button>
                <div class="row">
                <div class="col-md-12">
                    <div class="table-div shadow">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Button Name</th>
                                <th>Button Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
foreach(get_all_headersBtns() as $h_btns){
    $isOutline = ($h_btns['outline'] === 'on') ? 'checked' : '';
?>
                            <tr>
                                <td><?=$h_btns['name'];?></td>
                                <td class=""><a href="<?=$h_btns['link'];?>"><?=$h_btns['link'];?></a></td>
                                <td style="width:100px;"><button class="mx-1 btn btn-sm btn-outline-danger " onclick="delete_l('head_btn','<?=$h_btns['id'];?>');"><i class="fas fa-trash-alt"></i></button><button class="mx-1 btn btn-sm btn-outline-warning act-btn" onclick="edit_head_btn('<?=$h_btns['name'];?>','<?=$h_btns['link'];?>','<?=$isOutline;?>','<?=$h_btns['id'];?>');"><i class="fas fa-edit"></i></button></td>
                            </tr>
<?php }?>
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>

            <div class="mt-3">
            <h3>Cards</h3>
            <hr>
            <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="new_card();">Add new card</button>
                <div class="row">
                <div class="col-md-12">
                    <div class="table-div shadow" style="overflow:scroll;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
foreach(get_all_cards() as $cdata){
    $id = $cdata['id'];
    $img = base64_decode($cdata['img']);
    $title = $cdata['title'];
    $link = $cdata['link'];
?>
                            <tr>
                                <td width="100"><img class="img-fluid" src="./assets/imgs_uploads/<?=$img;?>"></td>
                                <td><?=$title;?></td>
                                <td><a href="<?=$link;?>"><?=$link;?></a></td>
                                <td style="width:100px;"><button class="mx-1 btn btn-sm btn-outline-danger " onclick="delete_l('cardz','<?=$id;?>');"><i class="fas fa-trash-alt"></i> Delete</button></td>
                            </tr>
<?php }?>
                        </tbody>
                    </table>
                    </div>
                </div>
        </div>

        <div class="mt-3">
            <h3>About page</h3>
            <hr>
        </div>
        <div class="row">
            <div class="mx-1 ">
                <label>Title:</label>
                <input class="form-control" autocomplete="off" name="about_title" value="<?=$web_data['about_title'];?>" placeholder="example..">
            </div>
            <div class="mx-1">
                <label>Description:</label>
                <div>
                    <textarea class="form-control" name="about_description" placeholder="Example..."><?=$web_data['about_desc'];?></textarea>
                </div>
            </div>
        </dvi>
        <div class="mt-3">
            <h3>Service Page</h3>
            <hr>
        </div>
        <div class="container">
            <!-- 
        <div class="row">
            <div class="row">
                <div class="col-md-2">
                    <label>Max service request per Member </label>
                    <input class="form-control" type="number" value="<?=$max_req;?>" name="service_max_no">
                </div>
            </div>
        </div>
             -->
            <div class="row">
                <div class="col-3">
                    <input class="form-control" hidden autocomplete="off" name="gcash_no" value="<?=$web_data['gcash_no'];?>" placeholder="Ex: 09123123123">
                    <label>Max service request per Member </label>
                    <input class="form-control" type="number" value="<?=$max_req;?>" name="service_max_no">
                </div>
                <div class="col-3">
                    <label>Fees: </label>
                    <input class="form-control" autocomplete="off" name="payment_amt" value="<?=$web_data['payment_amt'];?>" placeholder="Ex: 100">
                </div>
            </dvi>
        </div>
<!-- Start sv page types -->
        <!-- <button class="mt-2 mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="create_rtypes_btn();">Add new request type</button> -->

        <!-- <div class="row">
                <div class="col-md-12">
                    <div class="table-div shadow">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Request Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody> -->
<?php
foreach(get_all_rtypes() as $fdata){
    $id = $fdata['id'];
    $rt = $fdata['request_type'];
?>
                            <!-- <tr>
                                <td><?=$rt;?></td>
                                <td style="width:130px;"><button class="mx-1 btn btn-sm btn-outline-danger " onclick="delete_l('srvs_rtypes','<?=$id;?>');">Delete <i class="fas fa-trash-alt"></i></button></td>
                            </tr> -->
<?php }?>
                        <!-- </tbody>
                    </table>
                    </div>
                </div> -->
        </div>
<!-- End sv page types -->
        <div class="mt-3">
            <h3>Footer </h3>
            <hr>
            <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="create_footer_btn();">Add new link</button>
                <div class="row">
                <div class="col-md-12">
                    <div class="table-div shadow"  style="overflow: scroll;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Gov Links</th>
                                <th>Official Social Media Account</th>
                                <th>Contact Us</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
foreach(get_all_footerz() as $fdata){
    $isOutline = ($h_btns['outline'] === 'on') ? 'checked' : '';
    $id = $fdata['id'];
    $gov = $fdata['gov'];
    $social = $fdata['social'];
    $contact = $fdata['contact'];
?>
                            <tr>
                                <td><a href="<?=$gov;?>"><?=$gov;?></a></td>
                                <td><a href="<?=$social;?>"><?=$social;?></a></td>
                                <td><a href="<?=$contact;?>"><?=$contact;?></a></td>
                                <td style="width:100px;"><button class="mx-1 btn btn-sm btn-outline-danger " onclick="delete_l('footer_linkz','<?=$id;?>');"><i class="fas fa-trash-alt"></i></button><button class="mx-1 btn btn-sm btn-outline-warning act-btn" onclick="edit_footer_btn('<?=$id;?>','<?=$gov;?>','<?=$social;?>','<?=$contact;?>');"><i class="fas fa-edit"></i></button></td>
                            </tr>
<?php }?>
                        </tbody>
                    </table>
                    </div>
                </div>
        </div>
        <div class="row">
            
        </dvi>
        <div class="row">
            <div class="mt-3 mx-1 col-md-2">
                <input class="btn btn btn-primary" name="save_general_changes" onclick="save_page_change();" type="submit" value="Save changes">
            </div>
        </dvi>
    </div>
    <style>
        .image{
            padding:2px!important;
            border:2px solid rgb(0,0,0,0.3)!important;
            border-radius: 3px!important;
            overflow: hidden;
            max-height: 150px!important;
            max-width: 220px!important;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>
    <script>
function showReminder() {
  Swal.fire({
    title: 'Remember to save changes.',
    text: 'The save button is located at the bottom! or use keybind `ctrl + s`',
    icon: 'info',
    showCancelButton: true,
    confirmButtonText: 'Okay',
    cancelButtonText: "Don't show again"
  }).then((result) => {
    if (result.dismiss === Swal.DismissReason.cancel) {
      Cookies.set('hideReminder', 'true', { expires: 365 });
    }
  });
}

function remind(){
    const hideReminder = Cookies.get('hideReminder');
    if (!hideReminder) {
        showReminder(); 
    }
}
remind();

function save_page_change() {
  event.preventDefault();

<?php
if($_SESSION['sess_user_type'] === 0 ){
?>
  const elements = document.querySelectorAll('[name^="primary_color"], [name^="bg_color"], [name^="text_color"], [name^="service_max_no"], [name^="ciha_no"], [name^="polsta_no"], [name^="payment_amt"], [name^="gcash_no"], [name^="web_title"], [name^="meta_desc"], [name^="head_title"], [name^="about_title"], [name^="about_description"], [name^="save_general_changes"]');
<?php }else{?>
    const elements = document.querySelectorAll('[name^="service_max_no"], [name^="ciha_no"], [name^="polsta_no"], [name^="payment_amt"], [name^="gcash_no"], [name^="web_title"], [name^="meta_desc"], [name^="head_title"], [name^="about_title"], [name^="about_description"], [name^="save_general_changes"]');
<?php }?>


  const postDataObject = {};

  elements.forEach(function(element) {
    const name = element.getAttribute('name');
    const value = element.value;
    postDataObject[name] = value;
  });

  fetch(window.location.href, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams(postDataObject).toString()
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.text();
  })
  .then(data => {
    location.reload();
  })
  .catch(error => {
    console.error('Error:', error);
  });
}

document.addEventListener('keydown', function(event) {
  if ((event.ctrlKey || event.metaKey) && event.key === 's' || event.key === 'S') {
    save_page_change();
  }
});




    </script>
    <script src="./assets/js/a-dash-script.js"></script>

