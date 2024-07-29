<?php 
if(!isset($_GET['id']) || empty($_GET['id'])){
    echo "<script>location.href='/$c_role&v=cms_pages'</script>";
    exit;
}

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $data = data_extract($id, 'id', get_all_pages());
    $page_name = base64_decode($data['page_name']);
    $content = base64_decode($data['contents']);
}

?>
<div class="shadow d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
    <div class="d-flex align-items-center justify-content-center mx-2">
        <label for="">Page name: </label>
    </div>
    <div class="">
        <input class="form-control page-name" id="page_name" autofocus type="text" value="<?=$page_name;?>" required>
    </div>
</div>
<div style="font-size:16px;" class="shadow rounded">
<textarea id="markdown-editor-container"><?=$content;?></textarea>
</div>
<button class="shadow mt-2 mb-2 btn btn-outline-success" onclick="saveMD();">Save Changes</button>

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

const page = document.getElementById("page_name");


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

    function saveMD() {
        var markdownContent = simplemde.value();
        fetch('/<?=$c_role;?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'save_md_changes=&id=<?=$id;?>&mdcont='+btoa(markdownContent)+'&page_name='+btoa(page.value),
        })
        .then(response => response.text())
        .then(data => {
            // console.log(data);
            const res = data.trim().split("\n");
            const last = res[res.length - 1];
            // alert(last);
            if(last == 'true'){
                location.href="/<?=$c_role;?>&v=cms_pages";
            }else if(last == 'exists'){
                toast('Error page name already exists! ','2000','rgb(255,0,0,0.5)');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    page.onkeydown = (e) =>{
        const na = ['.',"'",'"','/',',','+','|','~','`','^','<','>','(',')','{','}','[',']',';',':','=','*','%','$','#','@','!',' ','\\'];
        const nak = ['v'];
        if(e.ctrlKey && nak.includes(e.key.toLowerCase())){
            e.preventDefault();
        }
        if( na.includes((e.key).toString())){
            toast('[Error]: Page name must not contain that symbol!','2000','rgb(255,0,0,0.6)');
            e.preventDefault();
        }
    }

</script>
