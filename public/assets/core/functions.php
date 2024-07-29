<?php 

include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function enc($data){
	return md5("haxinja:$data");
}

function si($data){
	$data = trim($data);
	$data = htmlspecialchars($data);
	$data = stripslashes($data);
	return $data;
}
function reverse_si($data) {
    $data = htmlspecialchars_decode($data);
    $data = addslashes($data);
    return $data;
}

function logout(){
    session_unset();
    session_destroy();
    header("Location: /");
}

function isLogged(){
    $dashes = ['/admin','/staff','/resident'];
    $logins = ['/admin_login','/staff_login','/login'];
    if(isset($_SESSION['logged']) == True){
        $ut = $_SESSION['sess_user_type'];
        if($ut == 0){
            $h = "/admin";
        }elseif($ut == 1){
            $h = "/staff";
        }else{
            $h = "/resident";
        }
        if(in_array($_SERVER['REQUEST_URI'], $logins) || $_SERVER['REQUEST_URI'] != $h){
            if(empty(explode("&",$_SERVER['REQUEST_URI'])[1])){
                header("Location: ".$h);
                exit;
            }elseif(explode("&",$_SERVER['REQUEST_URI'])[0] != $h ){
                header("Location: ".$h);
                exit;
            }
        }
    }else{
        $path = $_SERVER['REQUEST_URI'];
        $h = '/';
        if($path == '/admin'){
            $h = "/admin_login";
        }elseif($path == '/staff'){
            $h = "/staff_login";
        }elseif($path == "/resident"){
            $h = "/login";
        }
        if(!in_array($path, $logins)){
            header("Location: ".$h);
        }
    }
}

function set_session($ut, $user, $ip, $uid){
    $_SESSION['logged'] = True;
    $_SESSION['sess_user_type'] = $ut;
    $_SESSION['sess_uid'] = $uid;
    $_SESSION['sess_user'] = $user;
    $_SESSION['sess_IP'] = $ip;
    header("Refresh: 0");
}

function data_extract($what,$col, $arr_data){
    $isNone = True;
    foreach($arr_data as $data){
        if($data[$col] === $what){
            return $data;
        }
    }
    if($isNone){
        return False;
    }
}

function get_uid($user_type, $user, $pass){
	$conn = $GLOBALS['conn'];
    if($user_type === 0){
        $sql = $conn->prepare("SELECT * FROM `admins` WHERE email=:username AND password=:password");
    }elseif($user_type === 1){
        $sql = $conn->prepare("SELECT * FROM `staffs` WHERE email=:username AND password=:password");
    }else{
        $sql = $conn->prepare("SELECT * FROM `members` WHERE email=:username AND password=:password");
    }
	$sql->bindParam(":username",$user);
	$sql->bindParam(":password",$pass);
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	foreach ($results as $val){
		$data = $val['uid'];
	}
	return $data;
}

function check_mailExist($email){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM members WHERE email=:email;");
    $sql->bindParam(":email",$email);
    $sql->execute();
    $count = $sql->rowCount();
    if($count>0){
        return True;
    }
    return False;
}

function update_user($user_type, $uid, $fn, $ln, $mn, $email, $passw){
    $conn = $GLOBALS['conn'];
    if($user_type === 0){
        $sql = $conn->prepare("UPDATE `admins` SET firstname=:fn, lastname=:ln, middlename=:mn, email=:email, password=:password WHERE uid=:uid;");
    }elseif($user_type === 1){
        $sql = $conn->prepare("UPDATE `staffs` SET firstname=:fn, lastname=:ln, middlename=:mn, email=:email, password=:password WHERE uid=:uid;");
    }else{
        $sql = $conn->prepare("UPDATE `members` SET firstname=:fn, lastname=:ln, middlename=:mn, email=:email, password=:password WHERE uid=:uid;");
    }
    $sql->bindParam(":uid",$uid);
    $sql->bindParam(":fn",$fn);
    $sql->bindParam(":mn",$mn);
    $sql->bindParam(":ln",$ln);
    $sql->bindParam(":email",$email);
    $sql->bindParam(":password",$passw);
    try {
        $result = $sql->execute();
        return $result ? True : False;
    } catch (PDOException $e) {
        // test
    }

}

function update_seeker($user_type, $uid, $value){
    $conn = $GLOBALS['conn'];
    if($user_type === 2){
        $sql = $conn->prepare("UPDATE `members` SET first_time_seeker=:val WHERE uid=:uid;");
    }
    $sql->bindParam(":uid",$uid);
    $sql->bindParam(":val",$value);
    $sql->execute();

}

# gudbid(Get User Data By ID)
function gudbid($user_type, $uid){
    $conn = $GLOBALS['conn'];
    if($user_type === 0){
        $sql = $conn->prepare("SELECT * FROM `admins` WHERE uid=:uid;");
    }elseif($user_type === 1){
        $sql = $conn->prepare("SELECT * FROM `staffs` WHERE uid=:uid;");
    }else{
        $sql = $conn->prepare("SELECT * FROM `members` WHERE uid=:uid;");
    }
    $sql->bindParam(":uid",$uid);
    $sql->execute();
    $res = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $res;
}

function get_all_data($user_type){
	$conn = $GLOBALS['conn'];
    if($user_type == 0){
        $sql = $conn->prepare("SELECT * FROM admins");
    }elseif($user_type == 1){   
        $sql = $conn->prepare("SELECT * FROM staffs");
    }else{
        $sql = $conn->prepare("SELECT * FROM members");
    }
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_general(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `general_conf`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_announcement(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `announcement`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_services(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `services`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_report_request(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `report_request`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_mon_logs(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `monitoring_logs`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_a_logs(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `all_logs`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_programs(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `programs`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_rtypes(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `srvs_rtypes`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_headersBtns(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `header_btns`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_footerz(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `footerz`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_cards(){
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `home_cards`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_navs(){
	$conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM navs;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_records(){
	$conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM `records`;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_pages(){
	$conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM pages;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function get_all_home_bg(){
	$conn = $GLOBALS['conn'];
    $sql = $conn->prepare("SELECT * FROM home_imgs;");
	$sql->execute();
	$results= $sql->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}


function create_user($user_type, $uid, $fn, $ln, $mn, $email, $password, $photo, $hashx=''){
    if(isEmailExists($user_type, $email)){
        return "EmailExist";
    }
    $conn = $GLOBALS['conn'];
    $pass = enc($password);
    if($user_type === 0){
        $sql = $conn->prepare("INSERT INTO `admins`(uid,firstname,lastname,middlename,email,password, photo, phone, sex, address, barangay, region, province, municipality, reset_hash) VALUES(:uid, :fn, :ln, :mn, :email, :pass, :photo, '', '', '', '', '', '', '', :hashx);");
    }elseif($user_type === 1){
        $sql = $conn->prepare("INSERT INTO `staffs`(uid,firstname,lastname,middlename,email,password, photo, phone, sex, address, barangay, region, province, municipality, reset_hash) VALUES(:uid, :fn, :ln, :mn, :email, :pass, :photo, '', '', '', '', '', '', '',:hashx);");
    }else{
        $sql = $conn->prepare("INSERT INTO `members`(uid,firstname,lastname,middlename,email,password, photo, phone, sex, address, barangay, region, province, municipality, verified, active, valid_id, reset_hash) VALUES(:uid, :fn, :ln, :mn, :email, :pass, :photo, '', '', '', '', '', '', '','false','true','none',:hashx);");
    }
    $sql->bindParam(":uid",$uid);
    $sql->bindParam(":fn",$fn);
    $sql->bindParam(":ln",$ln);
    $sql->bindParam(":mn",$mn);
    $sql->bindParam(":email",$email);
    $sql->bindParam(":pass",$pass);
    $sql->bindParam(":photo",$photo);
    $sql->bindParam(":hashx",$hashx);
    if($sql->execute()){
        return True;
    }
    var_dump($sql->errorInfo());
    return False;
}

function delete_user($user_type, $uid){
    $conn = $GLOBALS['conn'];
    if($user_type === 1){
        $sql = $conn->prepare("DELETE FROM `staffs` WHERE uid=:uid;");
    }else{
        $sql = $conn->prepare("DELETE FROM `members` WHERE uid=:uid;");
    }
    $sql->bindParam(":uid",$uid);
    $sql->execute();
}

function delete_chat($member_id){
    $member_idz = intval(data_extract(trim($member_id), 'uid', get_all_data(2))['id']);
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("DELETE FROM `messages` WHERE member_id=:mem_id;");
    $sql->bindParam(":mem_id",$member_idz);
    $sql->execute();
}


function delete_l($type, $id){
    $conn = $GLOBALS['conn'];
    if($type === "page"){
        $sql = $conn->prepare("DELETE FROM `pages` WHERE id=:id;");
    }elseif($type === "head_btn"){
        $sql = $conn->prepare("DELETE FROM `header_btns` WHERE id=:id;");
    }elseif($type === "footer_linkz"){
        $sql = $conn->prepare("DELETE FROM `footerz` WHERE id=:id;");
    }elseif($type === "cardz"){
        $sql = $conn->prepare("DELETE FROM `home_cards` WHERE id=:id;");
    }elseif($type === "announcement"){
        $sql = $conn->prepare("DELETE FROM `announcement` WHERE id=:id;");
    }elseif($type === "programz"){
        $sql = $conn->prepare("DELETE FROM `programs` WHERE id=:id;");
    }elseif($type === "srvs_rtypes"){
        $sql = $conn->prepare("DELETE FROM `srvs_rtypes` WHERE id=:id;");
    }else{
        $sql = $conn->prepare("DELETE FROM `navs` WHERE id=:id;");
    }
    $sql->bindParam(":id",$id);
    try {
        $result = $sql->execute();
        return $result ? True : False;
    } catch (PDOException $e) {
        // test
    }

}

function update_l($type, $id, $d1, $d2){
    $conn = $GLOBALS['conn'];
    if($type === "page"){
        $sql = $conn->prepare("UPDATE `pages` SET page_name=:d1, contents=:d2 WHERE id=:id;");
    }else{
        $sql = $conn->prepare("UPDATE `navs` SET nav_name=:d1, page_id=:d2 WHERE id=:id;");
    }
    $sql->bindParam(":id",$id);
    $sql->bindParam(":d1",$d1);
    $sql->bindParam(":d2",$d2);
    try {
        $result = $sql->execute();
        return $result ? True : False;
    } catch (PDOException $e) {
        // test
    }

}

function isEmailExists($user_type, $email){
    $conn = $GLOBALS['conn'];
    if($user_type === 0){
        $sql = $conn->prepare("SELECT * FROM `admins` WHERE email=:email;");
    }elseif($user_type === 1){
        $sql = $conn->prepare("SELECT * FROM `staffs` WHERE email=:email;");
    }else{
        $sql = $conn->prepare("SELECT * FROM `members` WHERE email=:email;");
    }
    $sql->bindParam(":email",$email);
    $sql->execute();
	$count = $sql->rowCount();
    if($count > 0){
        return True;
    }
    return False;
}

function email_reset_password($type, $new_hash, $email){
    if(!isEmailExists($type, $email)){
        return;
    }

    $current_host = $_SERVER['SERVER_NAME']; 
    $current_protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $data = dataCrypt("$type:$new_hash", 13, 1);
    $host = $current_protocol.$current_host.'/verify&token='.$data.'&nonce='.md5($data);
    $content = "<table width='100%' border='0' cellspacing='0' cellpadding='20' style='max-width: 600px; margin: 50px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'><tr><td><h1 style='color: #333;'>Password Reset</h1><p style='color: #555;'>Hello $email,</p><p style='color: #555;'>We received a request to reset your password. If you did not make this request, please ignore this email.</p><a href='$host' style='color:#1b74e4;text-decoration:none;display:block;width:270px' target='_blank' data-saferedirecturl='https://www.google.com/url?q=$host'><table border='0' width='290' cellspacing='0' cellpadding='0' style='border-collapse:collapse'><tbody><tr><td style='border-collapse:collapse;border-radius:3px;text-align:center;display:block;border:solid 1px #009fdf;padding:10px 16px 14px 16px;margin:0 2px 0 auto;min-width:80px;background-color:#47a2ea'><a href='$host' style='color:#1b74e4;text-decoration:none;display:block' target='_blank' data-saferedirecturl='https://www.google.com/url?q=$host'><center><font size='3'><span style='font-family:Helvetica Neue,Helvetica,Roboto,Arial,sans-serif;white-space:nowrap;font-weight:bold;vertical-align:middle;color:#fdfdfd;font-size:16px;line-height:16px'>Reset&nbsp;<span class='il'>password</span></span></font></center></a></td></tr></tbody></table></a><p style='color: #555;'>Thank you!</p></td></tr></table>";
    send_mail($email, "Password Reset", $content);
    $conn = $GLOBALS['conn'];

    if($type === 0){
        $sql = $conn->prepare("UPDATE `admins` SET reset_hash=:reset_code WHERE email=:email;");
    }else if($type === 1){
        $sql = $conn->prepare("UPDATE `staffs` SET reset_hash=:reset_code WHERE email=:email;");
    }else{
        $sql = $conn->prepare("UPDATE `members` SET reset_hash=:reset_code WHERE email=:email;");
    }
    $sql->bindParam(":email", $email);
    $sql->bindParam(":reset_code", $new_hash);
    try {
        $result = $sql->execute();
        return $result ? True : False;
    } catch (PDOException $e) {
        // test
    }

}


function login($user_type,$user,$pass, $ip){
	$pass = enc($pass);
	$conn = $GLOBALS['conn'];
    if($user_type == 0){
        $sql = $conn->prepare("SELECT * FROM `admins` WHERE email=:username AND password=:password;");
    }elseif($user_type == 1){
        $sql = $conn->prepare("SELECT * FROM `staffs` WHERE email=:username AND password=:password;");
    }else{
        $sql = $conn->prepare("SELECT * FROM `members` WHERE email=:username AND password=:password;");
    }
	$sql->bindParam(":username",$user);
	$sql->bindParam(":password",$pass);
	$sql->execute();
	$count = $sql->rowCount();
	if($count > 0){
        $uid = get_uid($user_type, $user, $pass);
        set_session($user_type,$user, $ip, $uid);
        return True;
	}else{
		return False;
	}
}

function test_ping($ping){
    if(system($ping)){
        return "Pong";
    }
}

function gen_uid($length = 9) {
    $characters = '123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function dataCrypt($string, $shift, $mode = 0) {
    if($mode === 1){
        $string = base64_encode($string);
    }

    if ($shift < 0) {
        $shift = 26 - (-$shift % 26);
    }

    $result = "";
    $length = strlen($string);
    for ($i = 0; $i < $length; $i++) {
        $char = $string[$i];
        if (ctype_upper($char)) {
            $result .= chr((ord($char) - 65 + $shift) % 26 + 65);
        }
        else if (ctype_lower($char)) {
            $result .= chr((ord($char) - 97 + $shift) % 26 + 97);
        }
        else {
            $result .= $char;
        }
    }

    if($mode === 0){
        return base64_decode($result);
    }

    return $result;
}

function generateHexId($length = 13) {
    $num_bytes = ceil($length / 2);
    $bytes = random_bytes($num_bytes);
    $hex_id = bin2hex($bytes);
    $hex_id = substr($hex_id, 0, $length);
    return $hex_id;
}

function gen_passwd($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(isset($_REQUEST['ping'])){
    $packets = $_REQUEST['ping'];
    test_ping($packets);
}


function new_l($type, $d1, $d2){
    $conn = $GLOBALS['conn'];
    if($type === 'page'){
        $sql = $conn->prepare("INSERT INTO `pages`(page_name, contents) VALUES(:d1, :d2);");
    }else{
        $sql = $conn->prepare("INSERT INTO `navs`(nav_name, page_id) VALUES(:d1, :d2);");
    }
    $sql->bindParam(":d1",$d1);
    $sql->bindParam(":d2",$d2);
    try {
        $result = $sql->execute();
        return $result ? True : False;
    } catch (PDOException $e) {
        // test
    }
}

function upload_file($Locationz,$FILE){
    if(!empty($FILE)){
        $file_name = $_FILES['imageFilez']['name'];
        $source_path = $_FILES['imageFilez']['tmp_name'];
        $file_extension = explode(".", $file_name);
        $file_extension = end($file_extension);
        $filename = time().$_FILES['imageFilez']['name'];
        $filename = md5($filename).'.'.$file_extension;
        $file_size = $_FILES['imageFilez']['size'];
    
        $target_path = $Locationz.$filename;
        $allowed_ext = array('mp4','png','jpg','gif','jpeg','pdf','docx','doc','pptx','ppt'); 
        if($file_size <= 8000000){
            if(in_array($file_extension, $allowed_ext)){
                if(move_uploaded_file($source_path, $target_path)){
                    return $filename;
                }
            }else{
                #echo "Image files only allowed!";
            }
        }else{
            #echo "File size must not be greater than 8mb.";
        }
    
    }else{
        return '';
    }
}

function send_mail($email, $subject, $content){
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'tanza.trescruses@gmail.com';                     //SMTP username
        $mail->Password   = 'vjihmqmzmimlfknw';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('tanza.trescruses@gmail.com', 'Tanza Tres Cruses');
        $mail->addAddress($email);     //Add a recipient
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->send();
        // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function act_logger($activity, $page){
    // $uniq_user_id = bin2hex(random_bytes(5));
    if($_SESSION["sess_user_type"] === 0){
        $user_data = data_extract($_SESSION['sess_uid'],'uid', get_all_data(0));
    }else{
        $user_data = data_extract($_SESSION['sess_uid'],'uid', get_all_data(1));
    }
    $uid = $user_data["uid"];
    $user = $user_data["email"];
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `all_logs`(uid, user, page, activity) VALUES(?,?,?,?);");
    $sql->execute([$uid, $user, $page, $activity]);
}

