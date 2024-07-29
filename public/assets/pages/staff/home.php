<div class="row">
            <div class="col-xl-3 col-md-6">
               <div class="card y-gradient-cstm text-dark mb-4">
                  <div class="card-body fw-bold">
                     Messages
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="staff&v=messages">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="cstm-colorz fa-regular fa-handshake"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-md-6">
               <div class="card b-gradient-cstm text-dark mb-4">
                  <div class="card-body fw-bold">
                     Requested file
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="staff&v=monr_wir">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="text-danger fa-solid fa-magnifying-glass"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container ">
               <div>
                  <h4><b>News and Programs</b></h4>
               </div>

               <div id="headerCarousel" class="carousel slide" data-bs-ride="carousel">
                  <div class="carousel-inner">
                  <?php
$sql = $conn->prepare("SELECT * FROM `programs` ORDER BY id;");
$sql->execute();
$results = $sql->fetchAll(PDO::FETCH_ASSOC);
$isFirst = True;
for ($i = 0; $i < count($results); $i += 2) {
   $prog1 = $results[$i];
   $prog2 = ($i + 1 < count($results)) ? $results[$i + 1] : $results[0];

   $p1_id = $prog1['id'];
   $p1_title = base64_decode($prog1['title']);
   $p1_cover = base64_decode($prog1['cover']);
   $p1_content = base64_decode($prog1['content']);
   $p1_creation = base64_decode($prog1['date_created']);

   $p2_id = $prog2['id'];
   $p2_title = base64_decode($prog2['title']);
   $p2_cover = base64_decode($prog2['cover']);
   $p2_content = base64_decode($prog2['content']);
   $p2_creation = base64_decode($prog2['date_created']);

   $isActive = '';
   if($isFirst){$isActive = 'active';$isFirst = False;}



?>
                     <div class="carousel-item <?=$isActive;?>"> <!-- start carousel item -->
                        <div class="row"> <!-- start row -->

                           <div class="col-md-6" onclick="window.location.href='view_program&id=<?=$p1_id;?>';">
                              <div class="card">
                                 <img height="250" class="card-img-top" src="./assets/imgs_uploads/<?=$p1_cover;?>" alt="Image 1">
                                 <div class="card-body">
                                    <h5 class="card-title"><?=$p1_title;?></h5>
                                    <!-- <p class="card-text"><?=$p1_content;?></p> -->
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6" onclick="window.location.href='view_program&id=<?=$p2_id;?>';">
                              <div class="card">
                                 <img height="250" class="card-img-top" src="./assets/imgs_uploads/<?=$p2_cover;?>" alt="Image 2">
                                 <div class="card-body">
                                    <h5 class="card-title"><?=$p2_title;?></h5>
                                    <!-- <p class="card-text"><?=$p2_content;?></p> -->
                                 </div>
                              </div>
                           </div>

                        </div> <!-- end row -->
                     </div> <!-- end carousel item -->
<?php }?>
                  </div>
                  <div class="cc-b carousel-control-prev">
                     <a class="carousel-btn-main carousel-control-prev" href="#headerCarousel" role="button" data-bs-slide="prev">
                     <span class="rounded-circle cstm-carousel-btn material-symbols-outlined">arrow_back_ios_new</span>
                     </a>
                  </div>
                  <div class="cc-b carousel-control-next">
                     <a class="carousel-btn-main carousel-control-next" href="#headerCarousel" role="button" data-bs-slide="next">
                     <span class="rounded-circle cstm-carousel-btn material-symbols-outlined">arrow_forward_ios</span>
                     </a>
                  </div>
               </div>
            </div>
            <div class="container mt-4">
        <div class="row">
            <!-- First Div: Pending Request -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Request</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Request Type</th>
                                    <th>Date Requested</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
$navs_data = get_all_services();
$last_data = end($navs_data);
foreach($navs_data as $data){
    $status = trim($data['status']);
    if(empty($status) || $status != 'pending'){
        continue;
    }
    $id = intval($data['id']);
   //  $req_type = data_extract($data['request_type'], 'id', get_all_rtypes())?data_extract($data['request_type'], 'id', get_all_rtypes())['request_type']:'f';
    $req_type = data_extract(intval($data['request_type']), 'id', get_all_rtypes())["request_type"];
    $date_req = $data['date_created'];
    $view_btn = '<a href="/staff&v=request&id='.$id.'" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i> View</a>';
    $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.'</div>';
    $endz = ',';
    ?>
                                <tr>
                                    <td><?=$req_type;?></td>
                                    <td><?=$date_req;?></td>
                                    <td><?=$btns;?></td>
                                </tr>
<?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Second Div: Brgy.Calendar -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Brgy. Calendar</h5>
                        <div id="brgy-calendar">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
var calendarEl = document.getElementById('brgy-calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
   height: 450,
  initialView: 'dayGridMonth',
  events: [
<?php
$sql = $conn->prepare("SELECT * FROM `programs` ORDER BY id DESC LIMIT 3;");
$sql->execute();
$results = $sql->fetchAll(PDO::FETCH_ASSOC);
foreach($results as $c_data){
   $title = base64_decode($c_data['title']);
   $cdate = base64_decode($c_data['program_date']);
   if(empty(trim($cdate))){continue;}
?>
    {
      title: '<?=$title;?>',
      start: '<?=$cdate;?>',
    },
<?php }?>
  ],
});
calendar.render();

</script>


