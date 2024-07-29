<?php 

if(isset($_POST['create-event'])){
    foreach($_POST as $key => $val){
        $_POST[$key] = si($_POST[$key]);
    }
    $title = base64_encode($_POST['event-title']);
    $content = base64_encode($_POST['event-content']);
    $cover = upload_file('./assets/imgs_uploads/',$_FILES);

    if(empty($cover)){
        $cover = 'bg-img2.jpg';
    }
    $cover = base64_encode($cover);
    $conn = $GLOBALS['conn'];
    $sql = $conn->prepare("INSERT INTO `announcement`(cover, title, content) VALUES(:cover,:title,:content);");
    $sql->bindParam(":cover",$cover);
    $sql->bindParam(":title",$title);
    $sql->bindParam(":content",$content);
    $sql->execute();
    act_logger("Added new announcement with title '$title' ", $current_uri);

    echo "<script>window.location='$c_role&v=news_announcement';</script>";
}

?>
<div class="content-wrap container mb-2">
    <div class="form-wrap p-2">
        <div class="table-wrap d-flex justify-content-center">
            <form class="container" method="POST" enctype="multipart/form-data">
                <div class="d-lg-flex d-md-flex container">
                    <div class="text-center">
                        <img class="rounded w-100" id="prev-img-event" height="250px" src="../assets/imgs_uploads/bg-img2.jpg">
                        <label for="imageFilez" class="w-50 mt-2 btn btn-outline-primary">
                            <input onchange="sel_img('prev-img-event');" class="form-control d-none" type="file" id="imageFilez" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                            Change Image
                        </label> 
                    </div>
                    <div class="m-1 container ">
                        <div class="input-group mb-1">
                            <span class="input-group-text" id="basic-title">Title: </span>
                            <input class="form-control" aria-label="Title" aria-describedby="basic-title" type="text" name="event-title" placeholder="My title.." autocomplete="off">
                        </div>
                        <textarea id="markdown-editor-container" style="min-height:300px;" class="w-100 form-control mt-1" name="event-content" autocomplete="off" placeholder="Content.."></textarea>
                        <input class="w-50 btn btn-outline-success mt-2" type="submit" name="create-event" value="Create Announcement">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .search-btn input {
        outline:none;
        border-radius:5px;
        border:1px solid rgb(0,0,0,0.4);
    }
    .cstm-hover:hover {
        background-color:rgb(0,0,0,0.3);
        border-radius:10px;
    }
    .act-btn {
        font-size:18px;
    }
    .page-name:focus{
        box-shadow:none!important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<script>

function sel_img(eid){
    var file_path = document.getElementById("imageFilez");
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').at(-1);
    if(f_ext == 'jpg' || f_ext == 'gif' || f_ext == 'png' || f_ext == 'jpeg'){
      preview.src = URL.createObjectURL(file_path.files[0]);
      preview.classList.remove('cstm-hidden');
    }
}



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

    var simplemde = new SimpleMDE({ 
        element: document.getElementById("markdown-editor-container"),
        spellChecker: false,
        toolbar: [
        "bold",
        "italic",
        "heading",
        "|",
        "unordered-list",
        "ordered-list",
        "|",
        "link",
        "image",
        "|",
        "preview",
        ],
    });


</script>
