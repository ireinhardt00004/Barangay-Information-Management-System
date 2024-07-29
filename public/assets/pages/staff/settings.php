<?php 
if(isset($_POST['save_settings'])){
    foreach($_POST as $key => $value){
        $_POST[$key] = si($_POST[$key]);
    }
    $img = upload_file('./assets/imgs_uploads/',$_FILES);
    if(empty($img)){
        $img = base64_decode(data_extract($_SESSION['sess_uid'],'uid', get_all_data(1))['photo']);
        if(empty($img)){
            $img = 'img1.png';
        }
    }
    $photo = base64_encode($img);
    $fn = $_POST['fn'];
    $mn = $_POST['mn'];
    $ln = $_POST['ln'];
    $em = $_POST['email'];
    $phone = $_POST['phone'];
    $sex = $_POST['gender'];
    $address = $_POST['address'];
    $barangay= $_POST['barangay'];
    $region= $_POST['region'];
    $province= $_POST['province'];
    $municipality= $_POST['municipality'];

    $uid = $_SESSION['sess_uid'];
    $password = $_POST['password'];
    if(empty($password)){
        $password = data_extract($_SESSION['sess_uid'],'uid', get_all_data(1))['password'];
    }else{
        $password = enc($_POST['password']);
    }
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("UPDATE `staffs` SET firstname=:fn, middlename=:mn, lastname=:ln, email=:em, password=:password, photo=:photo, phone=:phone, sex=:sex, address=:address, barangay=:barangay, region=:region, province=:province, municipality=:municipality WHERE uid=:uid;");
    $sql->bindParam(":uid",$uid);
    $sql->bindParam(":fn",$fn);
    $sql->bindParam(":mn",$mn);
    $sql->bindParam(":ln",$ln);
    $sql->bindParam(":em",$em);
    $sql->bindParam(":phone",$phone);
    $sql->bindParam(":sex",$sex);
    $sql->bindParam(":address",$address);
    $sql->bindParam(":barangay",$barangay);
    $sql->bindParam(":region",$region);
    $sql->bindParam(":province",$province);
    $sql->bindParam(":municipality",$municipality);
    $sql->bindParam(":password",$password);
    $sql->bindParam(":photo",$photo);
    $sql->execute();
    echo "<script>window.location.href='staff&v=settings'</script>";
    exit;
}

$user_data = data_extract($_SESSION['sess_uid'],'uid', get_all_data(1));
$firstn = $user_data['firstname'];
$middlen = $user_data['middlename'];
$lastn = $user_data['lastname'];
$email = $user_data['email'];
$phone = $user_data['phone'];
$address = $user_data['address'];
$barangay = $user_data['barangay'];
$region = $user_data['region'];
$province = $user_data['province'];
$municipality = $user_data['municipality'];

$sex = $user_data['sex'];
$isMale = True;
if($sex == 'female'){
    $isMale = False;
}

$user_photo = base64_decode($user_data['photo']);
if(empty($user_photo)){
    $user_photo = 'img1.png';
 }
 
?>
                <!-- <img class="mx-auto" id="alb-prev-img" draggable="false" height="200" width="200" src="./assets/imgs_uploads/<?=$user_photo;?>">
                <label for="imageFilez-pr" class="mt-2 mx-auto d-block btn btn-outline-primary w-75">
                        <input onchange="sel_imgv2('alb-prev-img','imageFilez-pr');" class="w-75 d-none" type="file" id="imageFilez-pr" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                        Upload Image
                </label> -->
<form method="POST" enctype="multipart/form-data" class="h-100">
    <div class="container p-0 form-wrap">
        <div class="form-headz p-3">
            <h2>Personal Information</h2>
            <p class="text-muted">Update your personal information</p>
        </div>
        <div class="row p-0 m-0 justify-content-md-center">
            <div class="col d-flex p-3 mt-3">
                <div class="col-md-5">
                    <label>Profile Picture</label>
                </div>
                <div class="col-md-3">
                    <div class="mx-auto d-flex align-items-center justify-content-center p-4 profile-img-wrap">
                        <label for="imageFilez-pr" class="d-flex justify-content-end profile-img" id="alb-prev-img">
                            <input onchange="sel_imgv2('alb-prev-img','imageFilez-pr');" class="w-75 d-none" type="file" id="imageFilez-pr" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                            <i class="pfp-badge-i fa-regular fa-pen-to-square"></i>
                        </label>
                    </div>
                    <p style="font-size:13px;" class="mt-1"><b>NOTE:</b> Allowed file types: png, jpg, jpeg and gif. Please upload 1x1 or 2x2 picture.</p>
                </div>
            </div>
        </div>
        <div class="col p-0 m-0 justify-content-md-center">
            <div class="col d-flex p-3 mt-3">
                <label>First Name&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <div class="col-md-6 w-50 mx-auto">
                    <input class="ms-3 form-control" name="fn" value="<?=$firstn;?>">
                </div>
            </div>
            <div class="col d-flex p-3 mt-3">
                <label>Middle Name</label>
                <div class="col-md-7 mx-auto w-50">
                    <input class="ms-3 form-control" name="mn" value="<?=$middlen;?>">
                    <div class="ms-5" style="font-size:14px;">
                        <p>Please leave it blank if not applicable.</p>
                    </div>
                </div>
            </div>
            <div class="col d-flex p-3 mt-3">
                <label>Last Name</label>
                <div class="col-md-7 mx-auto w-50">
                    <input class="ms-4 form-control" name="ln" value="<?=$lastn;?>">
                    <div class="ms-5" style="font-size:14px;">
                        <!-- <p>Please leave it blank if not applicable.</p> -->
                    </div>
                </div>
            </div>
            <div class="col d-flex p-3 mt-3">
                <label>Sex</label>
                <div class="col-md-6 mx-auto">
                    <input type="radio" class="ms-5" <?=($isMale)?'checked':''?> name="gender" value="male">Male
                    <input type="radio" class="ms-5" <?=($isMale)?'':'checked'?> name="gender" value="female">Female
                </div>
            </div>
            <div class="col d-flex p-3 mt-3">
                <label>Contact Phone</label>
                <div class="col-md-6 mx-auto">
                    <input type="text" class="form-control" name="phone" value="<?=$phone;?>">
                </div>
            </div>
            <div class="col d-flex p-3 mt-3">
                <label>Email Address</label>
                <div class="col-md-6 mx-auto">
                    <input type="email" class="form-control" name="email" value="<?=$email;?>">
                </div>
            </div>
            <div class="col d-flex p-3 mt-3">
                <label>Password</label>
                <div class="col-md-7 mx-auto w-50">
                    <input type="password" class="ms-3 form-control" name="password">
                    <div class="ms-5" style="font-size:14px;">
                        <p>Please leave it blank if you dont wish to change your password.</p>
                    </div>
                </div>
            </div>
            <div class="col p-3 mt-3 d-flex">
                <div class="w-75">
                    <label>Street Address</label>
                    <input type="text" class="form-control" name="address" value="<?=$address;?>">
                </div>
                <div class="w-25 mx-4">
                    <label>Barangay</label>
                    <input type="text" class="form-control" name="barangay" value="<?=$barangay;?>">
                </div>
            </div>
            <div class="col p-3 mt-3 d-flex">
                <div class="w-100 mx-2">
                    <label>Region </label>
                    <input type="text" class="form-control" name="region" value="<?=$region;?>">
                </div>
                <div class="w-100 mx-2">
                    <label>Province </label>
                    <input type="text" class="form-control" name="province" value="<?=$province;?>">
                </div>
                <div class="w-100 mx-2">
                    <label>City/Municipality </label>
                    <input type="text" class="form-control" name="municipality" value="<?=$municipality;?>">
                </div>
            </div>
            <hr>
            <input class="btn btn-primary mx-3 mb-4" type="submit" value="Save Changes" name="save_settings">
        </div>
    </div>
</form>
<br>
<style>
:root{
    --cover-op: 0.3;
}
body,form{
    height:100vh;
}
.profile-img{
    transition: .3s;
    height:200px;
    width:200px;
    padding:5px;
    background-color:rgb(0,0,100,0.3);
    background: url('./assets/imgs_uploads/<?=$user_photo;?>');
    background-size:100%;
    background-repeat: no-repeat;
}
.profile-img:hover{
    transition: .5s!important;
    align-items: center;
    justify-content: center!important;
    background: linear-gradient( rgba(255,255,255, var(--cover-op)), rgba(0, 0, 0, var(--cover-op)) ), url('./assets/imgs/img-file.png');
    background-size:100%;
    background-repeat: no-repeat;
}

.pfp-badge-i{
    transition: .3s;
    font-size:18px;
}
.pfp-badge-i:hover{
    color:white;
    transition:.5s;
    transform: scale(2.0)!important;
}

.profile-img-wrap{
    transition: .3s;
    padding:5px;
    border-radius:6px;
    box-shadow:0px 0px 3px 1px rgb(0,0,0,0.3);
    border:3px solid rgb(0,0,0,0.1);
}
.profile-img-wrap:hover{
    transition: .5s;
    background-color:rgb(0,0,100,0.1);
}
.form-wrap{
    background-color:rgb(255,255,255,0.8);
    border-radius:3px;
    box-shadow:0px 0px 4px 0px rgb(0,0,0,0.1);
}
.form-headz{
    border-bottom: 1px solid rgb(0,0,0,0.1);
}
.form-headz h2{
    color:rgb(0,0,0,0.8);
    font-size:23px;
    font-weight:bold;
}
.form-headz p{
    font-size:14px;
}
</style>
<script>

function sel_imgv2(eid,iid){
    var file_path = document.getElementById(iid);
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').at(-1);
    if(f_ext == 'jpg' || f_ext == 'gif' || f_ext == 'png' || f_ext == 'jpeg'){
      urlz = URL.createObjectURL(file_path.files[0]);
      preview.style.backgroundImage = "url('"+urlz+"')";
      preview.classList.remove('cstm-hidden');
    }
}

</script>
    <link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

    