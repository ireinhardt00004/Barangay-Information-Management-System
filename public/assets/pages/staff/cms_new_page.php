<div class="shadow d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
    <div class="d-flex align-items-center justify-content-center mx-2">
        <label for="">Page name: </label>
    </div>
    <div class="">
        <input class="form-control page-name" id="page_name" autofocus type="text" required>
    </div>
</div>
<div style="font-size:16px;" class="shadow rounded">
<textarea id="markdown-editor-container" placeholder="Aa..."></textarea>
</div>
<button class="shadow mt-2 mb-2 btn btn-success" onclick="saveMD();">Create Page</button>

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
        if(page.value.trim() === ''){
            toast('Error name cannot be empty! ','2000','rgb(255,0,0,0.5)');
        }
        fetch('/<?=$c_role;?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'save_md=&mdcont='+btoa(markdownContent)+'&page_name='+btoa(page.value),
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            const res = data.trim().split("\n");
            const last = res[res.length - 1];
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
