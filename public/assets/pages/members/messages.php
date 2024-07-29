<?php
$uid = $_SESSION['sess_uid'];
$member_id = data_extract($uid, 'uid', get_all_data(2))['id'];

if(isset($_POST['new_msg'])){
    $message = si($_POST['msg']);
    $sql = $conn->prepare("INSERT INTO messages(receiver_id, member_id, message) VALUES(0, :mid, :msg);");
    $sql->bindParam(":mid",$member_id);
    $sql->bindParam(":msg",$message);
    $sql->execute();
}
?>
<div class="row mb-4">
            <div class="col-xl-3 col-md-6">
               <div class="card sg-gradient-cstm text-dark">
                  <div class="card-body fw-bold">
                     Dashboard
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="resident&v=home">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="fa-solid fa-table-columns"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-md-6">
               <div class="card b-gradient-cstm text-dark">
                  <div class="card-body fw-bold">
                     Requested file
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="resident&v=req_file">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="text-danger fa-solid fa-magnifying-glass"></i></div>
                     </div>
                  </div>
               </div>
            </div>

<!-- Message Start -->
<!-- style="background-color:rgb(0,0,60,0.1);" -->
<?php
// $sql = $conn->prepare("SELECT * FROM messages WHERE member_id=:id;");
// $sql->bindParam(':id',$member_id);
// $sql->execute();
// $results = $sql->fetchAll(PDO::FETCH_ASSOC);
// foreach($results as $res){
//     $staff_m = $res['receiver_id'];
//     $mem_m = $res['member_id'];
//     $msg = $res['message'];
//     if($staff_m != 0){
?>
            <div id="msg-container-wrap" class="shcontainer mt-3" style="height:80vh;">
                <div class="row cont-wrap">

                    <div class="h-100 col-md msg-box">
                        <div id="msgv-div" class="msg-view-area w-100">

                        </div>
                        <div>
                            <img height="70px" class="position-absolute" id="prev-img">
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <!-- <label for="imageFilez-pr" class="p-2">
                                <input onchange="sel_file('prev-img','imageFilez-pr');" class="form-control d-none" type="file" id="imageFilez-pr" name="imageFilez">
                                <i style="font-size:22px;" class="mbtn fa-solid fa-paperclip"></i>
                            </label> -->
                            <input id="text-msg-box" onkeydown="if(event.keyCode === 13){submit_msg();}" class="msg-tbox form-control rounded-pill" placeholder="Aa" type="text">
                            <button onclick="submit_msg();" class="send-btn mbtn"><i style="font-size:22px;" class="fa-regular fa-paper-plane"></i></button>
                        </div>

                    </div>

                </div>
                <br>
            </div>
<!-- Message End -->
</div>
    <script>

function sel_file(eid,iid){
    var file_path = document.getElementById(iid);
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').at(-1);
    if(f_ext == 'jpg' || f_ext == 'gif' || f_ext == 'png' || f_ext == 'jpeg'){
      preview.src = URL.createObjectURL(file_path.files[0]);
      preview.classList.remove('cstm-hidden');
    }
}

function sendMessage(message) {
    $.ajax({
        type: 'POST',
        url: '/resident&v=messages',
        data: { new_msg: true, msg: message },
        success: function(response) {
            console.log('Message sent successfully!');
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}

function submit_msg(){
    var messageFromInput = $('#text-msg-box');
    if ((messageFromInput.val()).trim() !== '') {
        sendMessage(messageFromInput.val());
        messageFromInput.val('');
    } else {
        console.error('Please enter a message!');
    }
    scrollToBottom();
}

function msg_type(type, msg, ts){
    const left = `<div class="w-100 m-0 mt-1 msg-left d-flex justify-content-start"><div class="chat-message m-0"><p class="m-0 p-2">`+msg+`</p><small class="text-muted">`+ts+`</small></div></div>`;
    const right = `<div class="w-100 m-0 mt-1 msg-right d-flex justify-content-end"> <div class="chat-message"> <p class="m-0 p-2">`+msg+`</p><small class="text-muted">`+ts+`</small></div> </div>`;
    if(type == 'left'){
        return left;
    }else{
        return right;
    }
}

function get_msgs(callback) {
    const xhr = new XMLHttpRequest();
    const url = '/resident';
    const params = 'fetch_msgs=&member_id=<?=$member_id;?>';

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = xhr.responseText.split('\n');
                const res = response[response.length - 1];
                callback(res); // Invoke the callback with the response
            } else {
                console.error('Request failed');
                callback(null); // Invoke the callback with an error or null
            }
        }
    };
    xhr.send(params);
}
let msg_len = 0;
setInterval(() => {
    get_msgs(function(new_msgz){
        new_msg = JSON.parse(new_msgz)
        let msg_box = document.getElementById("msgv-div");

        if(msg_len !== new_msg.length){
            new_msg.forEach((i)=>{
                const [t, m, ts] = i.split(':');
                msg_box.innerHTML+=(msg_type(t,atob(m), atob(ts)));
            });
            scrollToBottom();
            msg_len = new_msg.length;
        }
    });
}, 1000);


function scrollToBottom() {
    var scrollableDiv = document.getElementById('msgv-div');
    scrollableDiv.scrollTop = scrollableDiv.scrollHeight;
}

$(document).ready(function() {
        scrollToBottom();
        $("html, body").animate({ 
            scrollTop: $('#msg-container-wrap').offset().top 
        }, 'slow');
});

</script>
<style>
.cont-wrap{
    height:500px;
}
.msg-box{
    margin-left:5px;
    margin-right:5px;
    border-radius:8px;
    background-color: rgba(217,217,217,255);
    overflow:hidden;
}
.msg-search{
    border:none;
    outline:none;
    width:100%;
    font-size:14px;
    padding:5px;
    margin-bottom:10px;
    background-color:rgb(100,0,0,0.1)
}
.msg-tbox{
    border:none!important;
    box-shadow:none!important;
}
.msg-view-area{
    height:100%;
    max-height: 90%;
    overflow-X:hidden;
    overflow-Y:scroll;
}
.mbtn{
    color: rgba(225,203,124,255);
}
.send-btn{
    outline:none;
    border:none;
    background:none;
}
.chat-wrap{
    max-height:400px;
    /* background-color:black; */
    overflow-Y:scroll;
}
.user-chat{
    cursor:pointer;
    color: black!important;
    text-decoration:none;
}
.user-chat:hover{
    background-color: rgb(0,0,0,0.1);
}
.user-c-cont h3{
    font-size:14px;
}
.chat-message{
    max-width:300px;
}
.msg-left p{
    color: white;
    border-radius:15px;
    border-top-left-radius:0px!important;
    background-color: rgb(0,0,0,0.3);
    word-wrap: break-word; /* Added property for word wrapping */
    overflow-wrap: break-word; /* Optional: For better browser support */
}
.msg-right p{
    color: white;
    border-radius:15px;
    border-top-right-radius:0px!important;
    background-color: rgb(225,200,124,1);
    word-wrap: break-word; /* Added property for word wrapping */
    overflow-wrap: break-word; /* Optional: For better browser support */
}

</style>


