<?php

if(isset($_POST["save_changes"])){
    echo $_POST["primary"];
}

?>


<div class="container mt-4">
   <div class="row">
      <form method="POST">
      <div class="col-md-6 card">

        <div class="card-body row d-flex justify-content-center">
            <div class="col-6 mt-2">
                <div class="input-group d-flex justify-content-center">
                    <label class="input-group-text">Primary:</label>
                    <input type="color" name="primary" class="form-control form-control-color" id="myColor" value="#rgb(255, 225, 116)" title="Choose a color">
                </div>
            </div>
            <div class="col-6 mt-2">
                <div class="input-group d-flex justify-content-center">
                    <label class="input-group-text">Background:</label>
                    <input type="color" class="form-control form-control-color" id="myColor" value="#CCCCCC" title="Choose a color">
                </div>
            </div>
            <div class="col-6 mt-2">
                <div class="input-group d-flex justify-content-center">
                    <label class="input-group-text">Texts:</label>
                    <input type="color" class="form-control form-control-color" id="myColor" value="#CCCCCC" title="Choose a color">
                </div>
            </div>

        </div>
        <div class="card-footer">
            <input class="mt-2 btn btn-success" type="submit" name="save_changes" value="Save changes">
        </div>
      </div>
      </form>

   </div>
</div>
<script>
   var calendarEl = document.getElementById('brgy-calendar');
   var calendar = new FullCalendar.Calendar(calendarEl, {
      height: 450,
     initialView: 'dayGridMonth',
     events: [
       {
         title: 'Eveznt 1',
         start: '2023-11-10',
       },
       {
         title: 'Event 2',
         start: '2023-11-15',
       },
     ],
   });
   calendar.render();
   
</script>
