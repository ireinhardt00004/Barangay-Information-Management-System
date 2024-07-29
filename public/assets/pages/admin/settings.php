<?php 
if(isset($_POST['save_settings'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }
    $img = upload_file('./assets/imgs_uploads/',$_FILES);
    if(empty($img)){
        $img = base64_decode(data_extract($_SESSION['sess_uid'],'uid', get_all_data(0))['photo']);
        if(empty($img)){
            $img = 'img1.png';
        }
    }
    $photo = base64_encode($img);
    $fn = $_POST['fn'];
    $mn = $_POST['mn'];
    $ln = $_POST['ln'];
    $em = $_POST['email'];
    $uid = $_SESSION['sess_uid'];
    $password = $_POST['password'];
    if(empty($password)){
        $password = data_extract($_SESSION['sess_uid'],'uid', get_all_data(0))['password'];
    }else{
        $password = enc($_POST['password']);
    }
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("UPDATE `admins` SET firstname=:fn, middlename=:mn, lastname=:ln, email=:em, password=:password, photo=:photo WHERE uid=:uid;");
    $sql->bindParam(":uid",$uid);
    $sql->bindParam(":fn",$fn);
    $sql->bindParam(":mn",$mn);
    $sql->bindParam(":ln",$ln);
    $sql->bindParam(":em",$em);
    $sql->bindParam(":password",$password);
    $sql->bindParam(":photo",$photo);
    $sql->execute();
    echo "<script>window.location.href='admin&v=settings'</script>";
    exit;
}

$user_data = data_extract($_SESSION['sess_uid'],'uid', get_all_data(0));
$firstn = $user_data['firstname'];
$middlen = $user_data['middlename'];
$lastn = $user_data['lastname'];
$email = $user_data['email'];
$user_photo = base64_decode($user_data['photo']);
if(empty($user_photo)){
    $user_photo = 'img1.png';
 }

?>
<form method="POST" enctype="multipart/form-data">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center">
                <img class="mx-auto" id="alb-prev-img" draggable="false" height="200" width="200" src="./assets/imgs_uploads/<?=$user_photo;?>">
                <label for="imageFilez-pr" class="mt-2 mx-auto d-block btn btn-outline-primary w-75">
                        <input onchange="sel_imgv2('alb-prev-img','imageFilez-pr');" class="w-75 d-none" type="file" id="imageFilez-pr" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                        Upload Image
                </label>
                <input class="btn btn-success mt-2 w-75" name="save_settings" type="submit" value="Save changes">
            </div>
            <div class="col-md-5 text-center mx-auto">
                <div class="form-floating mb-2">
                    <input type="text" name="fn" value="<?=$firstn;?>" id="name_box3" placeholder="Name:" class="form-control">
                    <label for="name_box">Firstname: </label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" name="mn" value="<?=$middlen;?>" id="name_box1" placeholder="Name:" class="form-control">
                    <label for="name_box">Middlename: </label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" name="ln" id="name_box2" value="<?=$lastn;?>" placeholder="Name:" class="form-control">
                    <label for="name_box">Lastname: </label>
                </div>
                <div class="form-floating mb-2">
                    <input type="text" name="email" value="<?=$email;?>" id="name_box4" placeholder="Name:" class="form-control">
                    <label for="name_box">Email: </label>
                </div>
                <p>Leave password empty if you don't wish to change.</p>
                <div class="form-floating mb-2">
                    <input type="text" name="password" id="pass_box" placeholder="Name:" class="form-control">
                    <label for="name_box">Reset Password: </label>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

function sel_imgv2(eid,iid){
    var file_path = document.getElementById(iid);
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').at(-1);
    if(f_ext == 'jpg' || f_ext == 'gif' || f_ext == 'png' || f_ext == 'jpeg'){
      preview.src = URL.createObjectURL(file_path.files[0]);
      preview.classList.remove('cstm-hidden');
    }
}

</script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    