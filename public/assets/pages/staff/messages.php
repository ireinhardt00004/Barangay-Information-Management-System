<?php
$uid = $_SESSION['sess_uid'];
$staff_id = isset(data_extract($uid, 'uid', get_all_data(1))['id'])?data_extract($uid, 'uid', get_all_data(1))['id']:data_extract($uid, 'uid', get_all_data(0))['id'];
$member_id = (isset($_GET['id']))? $_GET['id']:'';

if(isset($_POST['new_msg'])){
    $member_id = $_POST['mem_id'];
    $message = si($_POST['msg']);
    $sql = $conn->prepare("INSERT INTO messages(receiver_id, member_id, message) VALUES(:sid, :mid, :msg);");
    $sql->bindParam(":sid",$staff_id);
    $sql->bindParam(":mid",$member_id);
    $sql->bindParam(":msg",$message);
    $sql->execute();
    exit;
}

?>
<div class="row mb-4">
            <div class="col-xl-3 col-md-6">
               <div class="card sg-gradient-cstm text-dark">
                  <div class="card-body fw-bold">
                     Dashboard
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="<?=$c_role;?>&v=home">View Details</a>
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
                        <a class="small text-dark stretched-link" href="<?=$c_role;?>&v=monr_wir">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="text-danger fa-solid fa-magnifying-glass"></i></div>
                     </div>
                  </div>
               </div>
            </div>
<!-- Message Start -->
<!-- style="background-color:rgb(0,0,60,0.1);" -->
            <div id="msg-container-wrap" class="shcontainer mt-3" style="height:80vh;">
                <div class="row cont-wrap">
                    <div class="col-md-3 msg-box mt-2">
                        <h4 class="mt-2"><b>Messages</b></h4>
                        <div>
                            <input onkeyup="chfilt();" class="msg-search rounded-pill" id="search_name" type="search" placeholder="Search name">
                        </div>
                        <div class="chat-wrap" id="chats-list">

                        </div>
                    </div>

                    <div class="h-100 col-md msg-box mt-2">
                        <div id="msgv-div" class="msg-view-area w-100">
<?php
$visible = False;
if(isset($_GET['id'])){
    $visible = True;

?>

<?php }else{?>
    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
        <h1 style="user-select:none;">No Chat Selected <i class="fa-solid fa-comments"></i></h1>
    </div>
<?php }?>
                        </div>
<?php 
if($visible){
?>
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
<?php }?>
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
        url: '/<?=$c_role;?>&v=messages',
        data: { new_msg: true, msg: message, mem_id:'<?=$member_id;?>' },
        success: function(response) {
            console.log(response);
            console.log('Message sent successfully!');
        },
        error: function(xhr, status, error) {
            console.error('Error: ' + error);
        }
    });
}

function submit_msg(){
    var messageFromInput = $('#text-msg-box');
    if ((messageFromInput.val()).trim() !== ''){
        sendMessage(messageFromInput.val());
        messageFromInput.val('');
    } else {
        console.error('Please enter a message!');
    }
    scrollToBottom();
}

function parse_chatlist(member_id, fullname, lastmsg, photo, time, css){
    return `<a id="chatid-`+member_id+`" href="<?=$c_role;?>&v=messages&id=`+member_id+`" class="`+css+` user-chat rounded d-flex p-1"> <img class="mt-2 rounded-circle" height="50" width="50" src="./assets/imgs_uploads/`+photo+`"> <div class="mt-2 mx-2 user-c-cont"> <h3><b>`+fullname+`</b></h3> <p>`+lastmsg+`<small class="text-muted mx-3">`+time+`</small></p> </div> </a>`;
}

function msg_type(type, msg, ts){
    const left = `<div class="w-100 m-0 mt-1 msg-left d-flex justify-content-start"><div class="chat-message m-0"><p class="m-0 p-2">`+msg+`</p><small class="text-muted">`+ts+`</small></div></div>`;
    const right = `<div class="w-100 m-0 mt-1 msg-right d-flex justify-content-end"> <div class="chat-message"> <p class="m-0 p-2">`+msg+`</p><small class="text-muted">`+ts+`</small></div> </div>`;
    if(type == 'left'){
        return right;
    }else{
        return left;
    }
}

function get_msgs(callback) {
    const xhr = new XMLHttpRequest();
    const url = '/<?=$c_role;?>';
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

function get_chats(callback) {
    const xhr = new XMLHttpRequest();
    const url = '/<?=$c_role;?>';
    const params = 'fetch_chats=true';

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
let chats_len = 0;
var chat_filter = '';

function chat_functions(){
    get_chats(function(chats){
        chat = JSON.parse(chats);
        let chat_list = document.getElementById("chats-list");
        chat_list.innerHTML='';
        if(chats_len !== chat.length){
            chat.forEach((i)=>{
                // m = member id
                // f = fullnam
                // l = last msg
                // p = profile
                // t = time(like 2 minutes ago)
                // c = css(like fw is bold if msg is seen or not.)
                const [m,f,l,p,t,c] = i.split(':');
                msg = atob(m);
                full = atob(f);
                last = atob(l);
                prof = atob(p);
                time = atob(t);
                css = atob(c);
                if(chat_filter.trim() === ''){
                    chat_list.innerHTML+=(parse_chatlist(msg,full,last,prof,time,css));
                }else if(full.toLowerCase().includes(chat_filter)){
                    chat_list.innerHTML+=(parse_chatlist(msg,full,last,prof,time,css));
                }
            });
            chat_len = chat.length;
        }
    });

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
}

chat_functions();
setInterval(() => {
    chat_functions();
    getElementById("msg-container-wrap").scrollIntoView();
}, 1500);

function chfilt(){
    chat_filter = document.getElementById("search_name").value;
    chat_functions();
}

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
html{
    scroll-behavior: smooth!important;
}
#chatid-<?=$_GET['id']?>{
    background-color:rgb(0,0,0,0.1);
}
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
    background-color: rgb(0,0,0,0.05);
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


