<?php

$isValid = $_SESSION['is_valid'] = True;
$_SESSION["err"] = "";
isLogged();

if(isset($_POST['resetpasswd'])){
    $email = $_POST['email'];
    $hash_verif = generateHexId(17);
    $_SESSION['err'] = "toast('Password reset sent. If the email you provided exists in our database, you will receive an email.','7000','rgb(0,0,255,0.3)');";
    email_reset_password(0, $hash_verif, $email);
}

if(isset($_POST['login'])){
    $user = $_POST['email'];
    $pass = $_POST['password'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $status = login(0,$user, $pass, $ip);
    if($status == False){
        $isValid = False;
    }
}

?>
<script>
function toast(msg, time,bgColor){
    Toastify({
  text: msg,
  duration: time,
  close: true,
  gravity: "top", 
  position: "right", 
  stopOnFocus: true, 
  style: {
    background: bgColor,
  },
}).showToast();    
}

<?php
if($isValid == False){
    echo "toast('Incorrect username or password','2000','rgb(255,0,0,0.3)');";
}

echo $_SESSION["err"];

?>
</script>
<div class="wrapper1 h-100 d-flex justify-content-center">
    <div class="container-fluid">
        <div class="row h-100">
            <div class="left-login col-12 col-sm-6 d-flex align-items-center justify-content-center">
                <img height="160" src="<?=$web_logo;?>">
            </div>
            <div class="right-login col-12 col-sm-6 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    <div class="d-md-none mb-4 d-sm-none d-flex align-items-center justify-content-center">
                        <img class="" height="160" src="<?=$web_logo;?>">
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="r-login-title">
                            <h2 class="ms-2"><b>ADMINISTRATOR<b></h2>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        <form id="login-form" method="POST" class="cstm-form">
                            <div class="cstm-ph-form">
                                <input type="email" name="email" id="emailbox" placeholder="Your email" autofocus required>
                            </div>
                            <div class="cstm-ph-form d-flex">
                                <input type="password" name="password" placeholder="Password" required>
                                <i onclick="toggle_passwd();" class="eye fa-solid fa-eye-slash"></i>
                            </div>
                            <div class="s-c-box d-flex align-items-center justify-content-center">
                                <div class="d-flex mx-auto ms-3">
                                    <input type="checkbox" checked>
                                    <p>Remember me</p>
                                </div>
                                <div class="mx-auto me-2">
                                    <input class="btn" type="submit" name="login" value="Login">
                                </div>
                            </div>
                            <div class="mt-4 d-flex flex-row text-center">
                                <a id="forgot-password-linkz" class="mx-auto" href="#" onclick="forgot_passwd();">Forgot password?</a>
                            </div>
                        </form>
                    </div>
                    <style>
                        ::placeholder {
                            color: rgb(238, 216, 100)!important;
                            opacity: 1; /* Firefox */
                        }
                        .cstm-form{
                            width:100%;
                            max-width:300px;
                        }
                        .cstm-ph-form{
                            margin:10px;
                            width:100%;
                            border-bottom:3px solid  rgb(238, 216, 137)!important;
                        }
                        .cstm-form a{
                            color: rgb(238, 216, 137)!important;
                            font-size:14px;
                        }
                        .cstm-ph-form input{
                            width: 100%;
                            outline:none;
                            border:none;
                        }
                        .cstm-form input[type=checkbox]{
                            border:1px solid rgb(238, 216, 137);
                            transform:scale(1.3);
                            accent-color: rgb(238, 216, 137);
                        }
                        .cstm-form input[type=submit]{
                            background-color: rgb(238, 216, 137)!important;
                            border-radius:8px;
                            /* font-weight:bold; */
                            color:white;
                        }
                        .eye{
                            padding:2px;
                            color: rgb(238, 216, 137);
                        }
                        .eye:hover{
                            cursor:pointer;
                        }
                        .cstm-form p{
                            margin: 9px;
                            font-size:14px;
                            color: rgb(238, 216, 137);
                        }
                        @media(max-width: 576px){
                            .left-login{
                                display: none!important;
                            }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="./assets/style.css">
<script>
    function rpass(email){
        Swal.fire({
            title: 'Confirm password reset?',
            html: `
            <form class="needs-validation" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <div class="d-flex justify-content-center">
                        <div class=" mx-auto w-100">
                            <input class="form-control disabled" type="text" readonly placeholder="Nav name" name="email" value="`+email+`">
                        </div>
                    </div>
                </div>
                <input class="btn btn-outline-success mx-auto" type="submit" name="resetpasswd" value="Confirm">
                <input class="btn btn-secondary mx-auto" type="button" onclick="swal.close();" value="Cancel">
            </form>
            `,
            showConfirmButton: false,
        });
    }

    function forgot_passwd(){
        const email = document.getElementById("emailbox");
        if(email.value.trim() === ''){
            toast('Email must not be empty!','6000','rgb(255,0,0,0.5)');
            return;
        }
        if(email.value.split("@").length !== 2 ||  email.value.split("@").length < 2){
            toast('Email must be valid!','6000','rgb(255,0,0,0.5)');
            return;
        }
        rpass(email.value);

    }

    const shw_pass = document.getElementById('showpasswd');
    document.querySelector('#register-link').addEventListener('click', function() {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
    });

    document.querySelector('#login-link').addEventListener('click', function() {
        document.getElementById('register-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    });

    function toggle_passwd(){
        var passwordInputs = document.querySelectorAll('input[name="password"]');
        var eyeIcons = document.querySelectorAll('.eye');
        passwordInputs.forEach(function(input, index) {
            input.type = (input.type === 'password') ? 'text' : 'password';
            if (input.type === 'text') {
                eyeIcons[index].classList.remove('fa-eye-slash');
                eyeIcons[index].classList.add('fa-eye');
            } else {
                eyeIcons[index].classList.remove('fa-eye');
                eyeIcons[index].classList.add('fa-eye-slash');
            }
        });
    };

    shw_pass.onclick = (t)=>{
        x = (shw_pass.innerHTML == '<i class="eye fa-solid fa-eye-slash"></i>Show password')? '<i class="eye fa-solid fa-eye"></i>Hide password':'<i class="eye fa-solid fa-eye-slash"></i>Show password';
        shw_pass.innerHTML=x;
        toggle_passwd();
    }

</script>

