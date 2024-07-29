<?php
$conn = $GLOBALS['conn'];


$sql = $conn->prepare("SELECT COUNT(CASE WHEN sex = '' THEN 1 END) AS uncat_count, COUNT(CASE WHEN sex = 'male' THEN 1 END) AS male_count, COUNT(CASE WHEN sex = 'female' THEN 1 END) AS female_count FROM members; ");
$sql->execute();
$results= $sql->fetch(PDO::FETCH_ASSOC);

$male_count = $results['male_count'];
$female_count = $results['female_count'];
$uncat_count = $results['uncat_count'];


$sql = $conn->prepare("SELECT COUNT(*) OVER () AS total_requests, DATE_FORMAT(date_created, '%m') AS month, COUNT(*) AS monthly_requests FROM services GROUP BY month ORDER BY month");
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
// print_r($monthly_requests);

// foreach ($monthly_requests as $month => $count) {
//    array_push($monthly_request_data, $count);
// }


?>
   <div class="row">
         <div class="col-xl-3 col-md-6">
               <div class="card y-gradient-cstm text-dark mb-4">
                  <div class="card-body fw-bold">
                     Staffs
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="admin&v=staffs">View Details</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="fa-solid fa-staff-snake"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-xl-3 col-md-6">
               <div class="card b-gradient-cstm text-dark mb-4">
                  <div class="card-body fw-bold">
                     All Logs
                     <div class="d-flex justify-content-between">
                        <a class="small text-dark stretched-link" href="admin&v=all_logs">View Logs</a>
                        <div class="text-dark" style="font-size:36px; margin-top:-25px;"><i class="fa-solid fa-shoe-prints"></i></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container mt-4">
        <div class="row">

                         <!-- Area Chart -->
                         <div class="col-xl-12 col-lg-12 mx-auto">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-dark">Service requests</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Number of Residents</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Male
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-danger"></i> Female
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-secondary"></i> Uncategorized
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

        </div>
        
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script>
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


var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Male", "Female", "Uncategorized"],
    datasets: [{
      data: [<?=$male_count;?>, <?=$female_count;?>, <?=$uncat_count;?>],
      backgroundColor: ['#4e73df', '#dc3444', '#6c747e'],
      hoverBackgroundColor: ['#2e59d9', '#dc3444', '#6c747e'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
   title: {
      display: true,
      text: "Total: <?=$male_count+$female_count+$uncat_count;?>"
    },
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 0,
  },
});



</script>
<style>
.cstm-text{
   color: #fbec89;
}
</style>
