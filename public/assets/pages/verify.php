<?php

if(isset($_POST["reset"])){
    $new_password = enc($_POST["newPassword"]);
    $token = $_POST["token"];
    $data = dataCrypt($token, 13, 0);

    $conn = $GLOBALS['conn'];
    $arr_data = explode(":", $data);
    $type = intval($arr_data[0]);
    $hash = $arr_data[1];

    if($type === 0){
        $sql = $conn->prepare("UPDATE `admins` SET reset_hash='', password=:new_pass WHERE reset_hash=:reset_code;");
    }else if($type === 1){
        $sql = $conn->prepare("UPDATE `staffs` SET reset_hash='', password=:new_pass WHERE reset_hash=:reset_code;");
    }else{
        $sql = $conn->prepare("UPDATE `members` SET reset_hash='', password=:new_pass WHERE reset_hash=:reset_code;");
    }
    $sql->bindParam(":reset_code", $hash);
    $sql->bindParam(":new_pass", $new_password);
    try {
        $result = $sql->execute();
        $status = $result ? "success" : "failed";
        echo $status;
        exit;
    } catch (PDOException $e) {
        // test
    }
}

if(!isset($_GET["token"]) && !isset($_GET["nonce"])){
    header("Location: /");
    exit;
}

$token = $_GET["token"];
$nonce = $_GET["nonce"];

if(md5($token) !== $nonce){
    header("Location: /");
    exit;
}

$data = dataCrypt($token, 13, 0);

$conn = $GLOBALS['conn'];
$arr_data = explode(":", $data);
$type = intval($arr_data[0]);
$hash = $arr_data[1];

if($type === 0){
    $sql = $conn->prepare("SELECT reset_hash FROM `admins` WHERE reset_hash=:reset_code;");
    $login_redirect = "/admin";
}else if($type === 1){
    $sql = $conn->prepare("SELECT reset_hash FROM `staffs` WHERE reset_hash=:reset_code;");
    $login_redirect = "/staff";
}else{
    $sql = $conn->prepare("SELECT reset_hash FROM `members` WHERE reset_hash=:reset_code;");
    $login_redirect = "/login";
}

$sql->bindParam(":reset_code", $hash);
$sql->execute();
$count = $sql->rowCount();
if($count < 1){
    header("Location: /");
    exit;
}


?>

<div class="semi-body d-flex align-items-center">
  <div class="container w-100">
    <div class="d-flex align-items-center justify-content-center">
      <div class="bg-white shadow rounded border p-3">
        <h3 class="mb-3">Create new password</h3>
        <small>Password must have at least 8 characters</small>
        <hr>
        <form id="resetPasswordForm" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="newPassword" class="form-label">New password</label>
            <div class="input-group">
              <input type="password" class="form-control shadow-none" name="newPassword" id="newPassword" placeholder="Enter Password" minlength="8" required>
              <button class="btn btn-outline-secondary toggle-password shadow-none" type="button">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm password:</label>
            <input type="password" class="form-control shadow-none" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" minlength="8" required>
            <div id="passwordMismatch" class="invalid-feedback">
              Passwords do not match
            </div>
          </div>
          <div id="passwordLengthError" class="invalid-feedback">
            Password must be at least 8 characters long
          </div>
          <input class="mt-1 mb-3 btn btn-success fw-bold" type="submit" name="reset" value="Reset password">
        </form>
      </div>
    </div>
  </div>
</div>

<style>
  :root {
    --cover-op: 0.8;
  }

  body {
    background-color: rgba(238, 225, 180, 0.9);
  }

  nav {
    background-color: rgb(0, 0, 0, 0.8);
  }

  .semi-body {
    height: 100vh;
  }

  .card {
    user-select: none;
    transition: .6s;
    color: white;
    overflow: hidden;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid white;
    box-shadow: 0px 3px 2px 1px rgb(0, 0, 0, 0.1);
    background: linear-gradient(rgba(0, 0, 0, var(--cover-op)), rgba(0, 0, 0, var(--cover-op))), url('./assets/imgs/logo.png');
    background-size: cover;
    background-position: center;
  }

  .card:hover {
    color: white;
    transition: .3s;
    transform: translateY(-15px);
    box-shadow: 0px 5px 10px 2px rgb(0, 0, 0, 0.5);
  }

  .card:active {
    transition: .1s;
    color: rgba(239, 227, 187, 1);
    transform: scale(1.1);
    box-shadow: none;
  }
</style>
<link rel="stylesheet" type="text/css" href="./assets/style.css">
<script>
  document.querySelector('.toggle-password').addEventListener('click', function () {
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    const newPasswordType = newPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    newPasswordInput.setAttribute('type', newPasswordType);

    const confirmPasswordType = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', confirmPasswordType);

    const eyeIcon = this.querySelector('i');
    eyeIcon.classList.toggle('fa-eye-slash');
  });

  document.getElementById('resetPasswordForm').addEventListener('submit', function (event) {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordMismatchError = document.getElementById('passwordMismatch');
    const passwordLengthError = document.getElementById('passwordLengthError');

    confirmPasswordInput.classList.remove('is-invalid');
    passwordMismatchError.style.display = 'none';
    passwordLengthError.style.display = 'none';

    let isValid = true;

    if (newPassword !== confirmPassword) {
      confirmPasswordInput.classList.add('is-invalid');
      passwordMismatchError.style.display = 'block';
      isValid = false;
    }

    if (newPassword.length < 8 || confirmPassword.length < 8) {
      confirmPasswordInput.classList.add('is-invalid');
      passwordLengthError.style.display = 'block';
      isValid = false;
    }

    if (isValid) {
      const formData = new FormData();
      formData.append('newPassword', newPassword);
      formData.append('reset', 'Reset password');
      formData.append('token', '<?=$_GET["token"];?>');

    fetch('/verify', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.text();
    })
    .then(data => {
      const status = (data.split("\n")).slice(-1)[0];
      if(status === "success"){
        Swal.fire({
          title: "Success!",
          text: "Your password has been reset.",
          icon: "success",
          allowOutsideClick: false,
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '<?=$login_redirect;?>';
          } else {
            window.location.href = '<?=$login_redirect;?>';
          }
        });
      }else{
        Swal.fire({
          title: "Failed!",
          text: "Password reset failed.",
          icon: "error",
          allowOutsideClick: false,
          showCancelButton: false,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Retry'
        }).then((result) => {
            location.reload();
        });
      }
    })
    .catch(error => {
      console.error('Error resetting password:', error);
    });

      event.preventDefault();
    } else {
      event.preventDefault();
    }
  });
</script>
