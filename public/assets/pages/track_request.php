<?php
$default->nav();
include('./assets/pages/Parsedown.php');
$Parsedown = new Parsedown();
$Parsedown->setSafeMode(true);
?>
<div class="semi-body d-flex align-items-center">
  <div class="container w-100">
    <div class="row d-flex align-items-center justify-content-center">
      <div class="col-md-10">
        <h2>Track Your Request</h2>
        <div class="input-group shadow">
          <input onkeydown="if(event.keyCode === 13){search();}" class="form-control shadow-none" placeholder="Enter tracking code" value="<?=(isset($_GET['c']))?$_GET['c']:'';?>" id="codebox" type="search">
          <button class="btn btn-primary" onclick="search();">Search</button>
        </div>
        <div>
          <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function search(){
    const code = document.getElementById("codebox").value;
    window.location.href='track_request&c='+code;
}

<?php
if(isset($_GET['c']) && !empty(trim($_GET['c']))){
    $code = si($_GET['c']);
    $sql = $conn->prepare("SELECT * FROM `services` WHERE tracking_code=:code;");
    $sql->bindParam(":code",$code);
    $sql->execute();
    try{
        $data = $sql->fetch(PDO::FETCH_ASSOC);
        if($data){
            $name = $data['fullname'] ?? '';
            $req_type = data_extract($data['request_type'], 'id', get_all_rtypes())['request_type'] ?? '';
            $pud = $data['pick_up_date'] ?? '';
            $dc = $data['date_created'] ?? '';
            $status = $data['status'] ?? '';
            $status = (!empty(trim($status))) ? ucfirst($status):'Requesting';
?>
            var tableData = [
                {
                    "name":"<?=$name;?>",
                    "service_type":"<?=$req_type;?>",
                    "pickup_date":"<?=$pud;?>",
                    "date_requested":"<?=$dc;?>",
                    "status":"<?=$status;?>",
                }
            ]

            var table = new Tabulator("#table-container", {
                data: tableData,
                placeholder: 'Empty Data',
                layout: "fitColumns",
                pagination: "local",
                paginationSize: 4,
                height: "100%",
                rowFormatter: function(row) {
                    row.getElement().style.height = "60px";
                },
                columns: [
                    { title: "Fullname", field: "name" },
                    { title: "Service Type", field: "service_type" },
                    { title: "Pick up Date", field: "pickup_date" },
                    { title: "Date Requested", field: "date_requested" },
                    { title: "Status", field: "status", formatter: "html"},
                ],
            });
<?php
        }else{
            echo "document.getElementById('table-container').innerHTML='<h3 disabled>No Results!</h3>';";
        }
    } catch(Exception $e){
        echo "document.getElementById('table-container').innerHTML='<h3 disabled>No Results!</h3>';";
    }
}?>
</script>
<style>
:root{
  --cover-op: 0.8;
}
body{
  background-color:rgba(238, 225, 180, 0.9);
}
nav{
    background-color:rgb(0,0,0,0.8);
}
.semi-body{
  height:100vh;
}
.card{
  user-select:none;
  transition: .6s;
  color:white;
  overflow: hidden;
  padding:15px;
  border-radius:10px;
  border: 1px solid white;
  box-shadow: 0px 3px 2px 1px rgb(0,0,0,0.1);
  background: linear-gradient( rgba(0,0,0, var(--cover-op)), rgba(0,0,0, var(--cover-op)) ), url('./assets/imgs/logo.png');
  background-size: cover;
  background-position: center;
}
.card:hover{
  color:white;
  transition:.3s;
  transform: translateY(-15px);
  box-shadow: 0px 5px 10px 2px rgb(0,0,0,0.5);
}
.card:active{
  transition:.1s;
  color: rgba(239,227,187,1);
  transform: scale(1.1);
  box-shadow:none;
}
</style>
<link rel="stylesheet" type="text/css" href="./assets/style.css">
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
