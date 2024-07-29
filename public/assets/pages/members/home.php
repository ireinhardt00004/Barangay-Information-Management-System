<?php

$conn = $GLOBALS['conn'];

$uuid = $_SESSION["sess_uid"];
$sql = $conn->prepare("SELECT COUNT(*) OVER () AS total_requests, DATE_FORMAT(date_created, '%m') AS month, COUNT(*) AS monthly_requests FROM services WHERE user_id=:id GROUP BY month ORDER BY month");
$sql->bindParam(":id", $uuid);
$sql->execute();

$results = $sql->fetchAll(PDO::FETCH_ASSOC);

$total_requests = 0;
$monthly_requests = array(
   "01" => 0, "02" => 0, "03" => 0,
   "04" => 0, "05" => 0, "06" => 0,
   "07" => 0, "08" => 0, "09" => 0,
   "10" => 0, "11" => 0, "12" => 0,
);

// $monthly_request_data = [];

foreach ($results as $row) {
   if ($total_requests == 0) {
      $total_requests = $row['total_requests'];  // All rows have the same total_requests value
   }
   $monthly_requests[$row['month']] = $row['monthly_requests'];
}

?>


<div class="row">
            <div class="col-xl-3 col-md-6">
               <div class="card y-gradient-cstm text-dark mb-4">
                  <div class="card-body fw-bold">
                     Messages
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="resident&v=messages">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="cstm-colorz fa-regular fa-handshake"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-md-6">
               <div class="card b-gradient-cstm text-dark mb-4">
                  <div class="card-body fw-bold">
                     Request file
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="resident&v=req_file">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="text-danger fa-solid fa-magnifying-glass"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container ">
               <div class="row">
                  <!-- Area Chart -->
                  <div class="col-xl-12 col-lg-12 mx-auto">
                      <div class="card shadow mb-4">
                          <!-- Card Header - Dropdown -->
                          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                              <h6 class="m-0 font-weight-bold text-dark">Number of requests</h6>
                          </div>
                          <!-- Card Body -->
                          <div class="card-body">
                              <div class="chart-area">
                                  <canvas id="myAreaChart"></canvas>
                              </div>
                          </div>
                      </div>
                  </div>

               </div>
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
<?php
$user_reqs = data_extract($_SESSION["sess_uid"], "user_id", get_all_services());
if($user_reqs !== False){
   $isShow = False;
   foreach(get_all_services() as $data){
      if($data["user_id"] !== $_SESSION["sess_uid"]){continue;}
      if($data["status"] === "pending"){
         $isShow = True;
         break;
      }
   }
   if($isShow){

?>
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
                                </tr>
                            </thead>
                            <tbody>
<?php
$navs_data = get_all_services();
$last_data = end($navs_data);
foreach($navs_data as $data){
    if($data["user_id"] !== $_SESSION["sess_uid"]){continue;}
    $status = trim($data['status']);
    if(empty($status) || $status != 'pending'){
        continue;
    }
    $id = $data['id'];
   //  $req_type = data_extract($data['request_type'], 'id', get_all_rtypes())['request_type'];
    $req_type = data_extract(intval($data['request_type']), 'id', get_all_rtypes()) ? data_extract(intval($data['request_type']), 'id', get_all_rtypes())['request_type'] : 'Err';

    $date_req = $data['date_created'];
    $view_btn = '<a href="/staff&v=request&id='.$id.'" class="mx-1 btn btn-sm btn-outline-primary"><i class="fa-regular fa-eye"></i> View</a>';
    $btns = '<div id="rowz'.$id.'" class="d-flex">'.$view_btn.'</div>';
    $endz = ',';
    ?>
                                <tr>
                                    <td><?=$req_type?></td>
                                    <td><?=$date_req;?></td>
                                </tr>
<?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
<?php }}?>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

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
<?php
}
$user_reqs = data_extract($_SESSION["sess_uid"], "user_id", get_all_services());

$navs_data = get_all_services();
$last_data = end($navs_data);
foreach($navs_data as $data){
   if($data["user_id"] !== $_SESSION["sess_uid"]){continue;}
    $status = trim($data['status']);
    $cdate = $data['date_created'];
    if(!empty($status) && $status !== 'pending'){
       $cdate = $data['date_modified'];
    }else{
      $status = "Requested";
    }
    $id = $data['id'];

    $cdate = explode(" ", $cdate)[0];
   //  $req_type = data_extract($data['request_type'], 'id', get_all_rtypes())['request_type'];
    $req_type = data_extract(intval($data['request_type']), 'id', get_all_rtypes()) ? data_extract(intval($data['request_type']), 'id', get_all_rtypes())['request_type'] : 'Err';
?>
   {
      title: '<?=$status;?> - <?=$req_type;?>',
      start: '<?=$cdate;?>',
      className: "fc-event-title",
   },
<?php }?>
  ],
  
  eventDidMount: function(info) {
      // Add Bootstrap tooltip to the event element
      var titleElement = info.el.querySelector('.fc-event-title');
      if (titleElement) {
        titleElement.setAttribute('data-bs-toggle', 'tooltip');
        titleElement.setAttribute('data-bs-placement', 'top');
        titleElement.setAttribute('title', info.event.title);

        // Initialize the tooltip
        var tooltip = new bootstrap.Tooltip(titleElement);

        // Show tooltip on click
        titleElement.addEventListener('click', function() {
          tooltip.show();
        });

        // Hide tooltip on mouse leave
        titleElement.addEventListener('mouseleave', function() {
          tooltip.hide();
        });
      }
    }

});
calendar.render();




// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    datasets: [{
      label: "Requests",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgb(240,220,140)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: [
         <?php
         foreach($monthly_requests as $k => $v){
            echo $v.',';
         }
         ?>
      ],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 5,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 7
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,

          callback: function(value, index, values) {
            return number_format(value);
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});

Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';



</script>
<style>

#brgy-calendar {
  max-width: 100%;
  margin: 20px auto;
}

.fc-event-title {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: inline-block;
  max-width: 100%;
}

.marquee {
  animation: marquee 5s linear infinite;
}

@keyframes marquee {
  0% { transform: translateX(100%); }
  100% { transform: translateX(-100%); }
}
</style>

