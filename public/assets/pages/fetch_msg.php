<?php
include('./assets/core/config.php');

if(isset($_POST['fetch_msgs'])){
    // $member_id = $_POST['member_id'];
    $member_id = 15;
    $sql = $conn->prepare("SELECT * FROM messages WHERE member_id=:id;");
    $sql->bindParam(':id',$member_id);
    $sql->execute();
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);
    $all_msgs = [];
    foreach($results as $res){
       $staff_m = $res['receiver_id'];
       $mem_m = $res['member_id'];
       $msg = $res['message'];
       if($staff_m != 0){
          $msg_type = 'left:';
       }else{
          $msg_type = 'right:';
       }
       array_push($all_msgs, $msg_type.base64_encode($msg));
    }
    echo json_encode($all_msgs);
    exit;
 }
 