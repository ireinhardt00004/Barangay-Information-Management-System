
<div class="content-wrap container mb-2">
    <div class="form-wrap p-2">
        <h2>Programs</h2>
        <a href="<?=$c_role;?>&v=create_program" class="btn btn-sm btn-outline-primary rounded-pill mb-2">
            <i class=" rounded-circle fa-regular fa-plus"></i> Create new
        </a>

        <div class=" container table-responsive">
<?php
$conn = $GLOBALS['conn'];
$sql = $conn->prepare("SELECT * FROM `programs` ORDER BY id DESC;");
$sql->execute();
$results = $sql->fetchAll(PDO::FETCH_ASSOC);
if(!empty($results)){
?>
            <table class="table table-light table-striped table-bordered mt-2 rounded" style="font-size:18px;">
                <thead class="text-center mb-5">
                    <tr>
                        <th class="text-muted">Cover</th>
                        <th class="text-muted">Title</th>
                        <th class="text-muted">Program date</th>
                        <th class="text-muted">Content</th>
                        <th class="text-muted" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php
}

foreach($results as $row){
    $id = $row['id'];
    foreach($row as $key => $value){$row[$key] = base64_decode($value);}
    $img = $row['cover'];
    $name = $row['title'];
    $pd = $row['program_date'];
    $desc = $row['content'];
    if(strlen($desc)>65){
        $desc = substr($desc,0,65)."...";
    }
?>
                    <tr id="<?=$id;?>">
                        <td><img height="70" class="rounded" width="70" src="../assets/imgs_uploads/<?=$img;?>"></td>
                        <td><?=$name;?></td>
                        <td><?=$pd;?></td>
                        <td><?=$desc;?></td>
                        <td><a class="btn btn-outline-primary w-100" href="<?=$c_role;?>&v=edit_program&id=<?=$id;?>">Edit</a></td>
                        <!-- <td><a class="btn btn-outline-warning w-100" href="'.$url.'">Reset</a></td> -->
                        <td><a class="btn btn-outline-danger w-100" onclick="delete_l('programz','<?=$id?>');">Delete</a></td>
                    </tr>
<?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function delete_l(type,id){
    Swal.fire({
        title: 'Do you confirm to delete this?',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="type" value="`+type+`">
            <input class="d-none" name="id" value="`+id+`">
            <input class="btn btn-outline-danger m-3" type="submit" name="delete_l" value="I Confirm">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });    
}

</script>
<style>

th{
    top:0;
    position:sticky;
    backdrop-filter: blur(6px);
    background-color: rgb(200,200,200,0.2);
    box-shadow: 0px 2px 3px rgb(0,0,0,0.9);
}
.form-wrap{
    box-shadow: 0px 0px 5px grey;
    border-radius: 10px;
}
.table-wrap{
    max-height: 410px;
    overflow: scroll;
}
.content-wrap{
    max-width: 1000px;
}
table{
    position:relative
}
tbody a{
    font-weight: bold!important;
}
</style>

