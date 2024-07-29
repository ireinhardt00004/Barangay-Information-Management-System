<?php
if(isset($_GET['id']) && !empty(trim($_GET['id']))){
    $id = intval($_GET['id']);
    $data = data_extract($id, 'id', get_all_services());
    if($data == false){
      echo "<script>window.location.href='/resident&v=requested'</script>";
    }
    if($data["user_id"] !== $_SESSION["sess_uid"]){
        echo "<script>window.location.href='/resident&v=requested'</script>";
        exit;
    }
    $tc = $data['tracking_code'];
    $req_type = data_extract(intval($data['request_type']), 'id', get_all_rtypes())['request_type'];
    // r_data = request data
    // echo $data["data"];
    $r_data = json_decode($data["data"]);

}else{
    echo "<script>window.location.href='/resident&v=requested'</script>";
    exit;
}
?>

<div class="h-100">
<button class="btn btn-outline-warning" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i>Go Back</button>

<div class=" container d-flex main-c justify-content-center mb-3">
    <div class="w-100 h-100"> <!-- Start of annoucement -->
        <div class="row p-1 rounded d-flex justify-content-center"><!-- Start of Card Container -->

            <div class="col p-0" style="background-color: rgb(255,255,255,0.8); border-radius:10px; max-width:700px;">
                <div>
                      <div class="input-group mb-2">
                        <span class="input-group-text">Request Type:</span>
                        <select disabled style="font-weight:bold;background-color:white;" class="text-primary form-select" name="rtype" required>
                          <option selected><?=$req_type;?></option>
                        </select>
                      </div>
                      <div class="container">
                        <div class="row">
                          <div class="form-group">
                            <label>Tracking Code</label>
                            <input disabled class="form-control" name="tracking_code" value="<?=$tc;?>" readonly>
                          </div>
                          <!-- Start service view -->
                          <div id="service_view">
                            
                          </div>
                          <!-- End service View -->
                          <div class="mt-4 mb-4">
                          </div>
                        </div>
                      </div>

                </div>                
            </div>
        </div><!-- End of Card Container -->
    </div><!-- End of Announcement -->
</div>
</div>


<style>
body{
  background-color:rgba(238, 225, 180, 0.9);
}
nav{
    background-color:rgb(0,0,0,0.8);
}
.main-c{
    height:100%;
}
.pm-box{
    /* background-color:rgba(238, 225, 180, 0.9); */
    background-color:white;
    border-radius:10px;
}
.pm-box-info p{
    margin:0;
}
  .cstm-img-box{
    border: 1px solid rgb(0,0,0,0.3);
    border-radius:10px;
    transition: .4s;
  }
  .cstm-img-box:hover{
    border: 1px solid rgb(0,0,0,0.1);
    box-shadow: 0px 5px 5px 1px rgb(0,0,0,0.5)!important;
    transition: .2s;
    transform: translateY(-5px);
  }

  .cstm-card {

border: 1px solid #ccc;
border-radius: 5px;
margin-bottom: 15px;
overflow: hidden; 
}

.cstm-card-content {
padding: 15px;
}

.cstm-card-content p, .linkz-footer {
max-height: 100px; 
overflow: hidden;
text-overflow: ellipsis;
word-wrap: break-word;
line-height: 1.5em;
}
.read-data{
    background-color:white!important;
}
</style>

<link rel="stylesheet" type="text/css" href="./assets/style.css">
<script>
const BarangayClearance = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTYgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+RGF0ZSBvZiBCaXJ0aDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9ImRhdGUiIG5hbWU9ImRvYiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNiBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5BZ2U8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJudW1iZXIiIG5hbWU9ImFnZSIgcGxhY2Vob2xkZXI9IkVudGVyIHlvdXIgYWdlIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Im10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QbGFjZSBvZiBCaXJ0aDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJwb2IiIHBsYWNlaG9sZGVyPSJFbnRlciBwbGFjZSBvZiBiaXJ0aCIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Im10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5Ib3VzZSBBZGRyZXNzPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIG5hbWU9ImhvdXNlQWRkcmVzcyIgcGxhY2Vob2xkZXI9IkhvdXNlIE5vLCBvciBCbG9jaywgTG90LCBQaGFzZSwgU3RyZWV0LCBTdWJkaXZpc2lvbiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Im10LTIiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+UHVycG9zZSBvZiBnZXR0aW5nIGJhcmFuZ2F5IGNsZWFyYW5jZTwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx0ZXh0YXJlYSBjbGFzcz0iZm9ybS1jb250cm9sIiBuYW1lPSJwdXJwb3NlIiBwbGFjZWhvbGRlcj0iRW50ZXIgUHVycG9zZSI+PC90ZXh0YXJlYT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2Pg==";
const CertificateResidency = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTYgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+RGF0ZSBvZiBSZXNpZGVuY3k8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJkb3IiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgY2VydGlmaWNhdGUgcmVzaWRlbmN5PC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9InB1cnBvc2UiIHBsYWNlaG9sZGVyPSJFbnRlciBQdXJwb3NlIj48L3RleHRhcmVhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const CertificateIndigency = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgY2VydGlmaWNhdGUgaW5kaWdlbmN5PC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPHRleHRhcmVhIGNsYXNzPSJmb3JtLWNvbnRyb2wiIG5hbWU9InB1cnBvc2UiIHBsYWNlaG9sZGVyPSJFbnRlciBQdXJwb3NlIj48L3RleHRhcmVhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const OathOfUndertaking = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9ImZ1bGxuYW1lIiBwbGFjZWhvbGRlcj0iRW50ZXIgeW91ciBuYW1lIiB2YWx1ZT0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTYgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+RGF0ZSBvZiBSZXNpZGVuY3k8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJkb3IiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMiI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5QdXJwb3NlIG9mIGdldHRpbmcgZmlyc3QgdGltZSBzZWVrZXIgY2VydGlmaWNhdGlvbjwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDx0ZXh0YXJlYSBjbGFzcz0iZm9ybS1jb250cm9sIiBuYW1lPSJwdXJwb3NlIiBwbGFjZWhvbGRlcj0iRW50ZXIgUHVycG9zZSI+PC90ZXh0YXJlYT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2Pg==";
const BusinessClearance = "PGRpdiBjbGFzcz0ibXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5CdXNpbmVzcyBOYW1lPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJidXNpbmVzc05hbWUiIHBsYWNlaG9sZGVyPSJFbnRlciB5b3VyIGJ1c2luZXNzIG5hbWUiIHZhbHVlPSIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+QnVzaW5lc3MgQWRkcmVzczwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJidXNpbmVzc0FkZHJlc3MiIHBsYWNlaG9sZGVyPSJCbGRnIE5vLCBvciBCbG9jaywgTG90LCBQaGFzZSwgU3RyZWV0LCBTdWJkaXZpc2lvbiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9Im10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5OYW1lIG9mIFRoZSBPd25lcjwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJvd25lck5hbWUiIHBsYWNlaG9sZGVyPSJPd25lcidzIE5hbWUiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNiBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+SXNzdWFuY2UgRGF0ZTwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJkYXRlIiBuYW1lPSJpc3N1YW5jZURhdGUiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";
const BarangayID = "PGRpdiBjbGFzcz0icm93Ij4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMgbXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlN1cm5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBhdXRvY29tcGxldGU9Im9mZiIgbmFtZT0ic3VybmFtZSIgcGxhY2Vob2xkZXI9IiIgdmFsdWU9IiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNCBtdC0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+Rmlyc3QgTmFtZTwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJmaXJzdE5hbWUiIHBsYWNlaG9sZGVyPSIiIHZhbHVlPSIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMgbXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPk1pZGRsZSBOYW1lPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgYXV0b2NvbXBsZXRlPSJvZmYiIG5hbWU9Im1pZGRsZU5hbWUiIHBsYWNlaG9sZGVyPSIiIHZhbHVlPSIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTIgbXQtMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlN1ZmZpeDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIGF1dG9jb21wbGV0ZT0ib2ZmIiBuYW1lPSJzdWZmaXgiIHBsYWNlaG9sZGVyPSIiIHZhbHVlPSIiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0ibXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkhvdXNlIEFkZHJlc3M8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iaG91c2VBZGRyZXNzIiBwbGFjZWhvbGRlcj0iSG91c2UgTm8sIG9yIEJsb2NrLCBMb3QsIFBoYXNlLCBTdHJlZXQsIFN1YmRpdmlzaW9uIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPCEtLSBTdGFydCBSb3cgLS0+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJyb3ciPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+Q2l2aWwgU3RhdHVzPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImZvcm0tY2hlY2siPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNoZWNrLWlucHV0IiB0eXBlPSJyYWRpbyIgbmFtZT0iY2l2aWxfc3RhdHVzIiBpZD0iY1NpbmdsZSIgdmFsdWU9IlNpbmdsZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWwgY2xhc3M9ImZvcm0tY2hlY2stbGFiZWwiIGZvcj0iY1NpbmdsZSI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIFNpbmdsZQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJmb3JtLWNoZWNrIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jaGVjay1pbnB1dCIgdHlwZT0icmFkaW8iIG5hbWU9ImNpdmlsX3N0YXR1cyIgaWQ9ImNNYXJyaWVkIiB2YWx1ZT0iTWFycmllZCI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWwgY2xhc3M9ImZvcm0tY2hlY2stbGFiZWwiIGZvcj0iY01hcnJpZWQiPk1hcnJpZWQ8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImZvcm0tY2hlY2siPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNoZWNrLWlucHV0IiB0eXBlPSJyYWRpbyIgbmFtZT0iY2l2aWxfc3RhdHVzIiBpZD0iY1dpZG93ZWQiIHZhbHVlPSJXaWRvd2VkIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbCBjbGFzcz0iZm9ybS1jaGVjay1sYWJlbCIgZm9yPSJjV2lkb3dlZCI+V2lkb3dlZDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtMyBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5EYXRlIG9mIEJpcnRoPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0iZGF0ZSIgbmFtZT0iZG9iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC0zIG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkJsb29kIFR5cGU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJibG9vZFR5cGUiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGRpdiBjbGFzcz0iY29sLTMgbXQtMyBtYi0zIj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8bGFiZWw+UmVsaWdpb248L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJyZWxpZ2lvbiIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtMyBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5DZWxscGhvbmUgTnVtYmVyPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9ImZvcm0tY29udHJvbCIgdHlwZT0idGV4dCIgbmFtZT0iY3BOdW1iZXIiIHJlcXVpcmVkPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KCiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8L2Rpdj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwhLS0gRW5kIFJvdyAtLT4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxocj4KICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5FbWVyZ2VuY3kgQ29udGFjdDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJyb3ciPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8ZGl2IGNsYXNzPSJjb2wtNCBtdC0zIG1iLTMiPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxsYWJlbD5GdWxsIE5hbWU8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJlbWVyZ2VuY3lOYW1lIiBwbGFjZWhvbGRlcj0iIiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC00IG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPkNlbGxwaG9uZSBOdW1iZXI8L2xhYmVsPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxpbnB1dCBjbGFzcz0iZm9ybS1jb250cm9sIiB0eXBlPSJ0ZXh0IiBuYW1lPSJlbWVyZ2VuY3lDb250YWN0IiByZXF1aXJlZD4KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDxkaXYgY2xhc3M9ImNvbC00IG10LTMgbWItMyI+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGxhYmVsPlJlbGF0aW9uc2hpcDwvbGFiZWw+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPSJmb3JtLWNvbnRyb2wiIHR5cGU9InRleHQiIG5hbWU9ImVtZXJnZW5jeVJlbGF0aW9uc2hpcCIgcmVxdWlyZWQ+CiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIDwvZGl2PgogICAgICAgICAgICAgICAgICAgICAgICAgICAgPC9kaXY+";

var formData = <?=$data["data"];?>;
// document.addEventListener("DOMContentLoaded", function() {
//   for (const key in formData) {
//     if (formData.hasOwnProperty(key)) {
//       const inputElement = document.querySelector(`input[name="${key}"]`);
//       const textareaElement = document.querySelector(`textarea[name="${key}"]`);
//       if (inputElement) {
//         inputElement.value = formData[key];
//       } else if (textareaElement) {
//         textareaElement.value = formData[key];
//       }
//     }
//   }
// });
document.addEventListener("DOMContentLoaded", function() {
        for (const key in formData) {
            if (formData.hasOwnProperty(key)) {
                const value = formData[key];
                const inputElement = document.querySelector(`input[name="${key}"]`);
                const textareaElement = document.querySelector(`textarea[name="${key}"]`);
                const radioElements = document.querySelectorAll(`input[name="${key}"][type="radio"]`);

                if (inputElement && inputElement.type !== "radio") {
                    inputElement.value = value;
                } else if (textareaElement) {
                    textareaElement.value = value;
                } else if (radioElements.length > 0) {
                    radioElements.forEach(radio => {
                        if (radio.value === value) {
                            radio.checked = true;
                        }
                    });
                }
            }
        }
    });

var service_view = document.getElementById("service_view");
var request_typex = parseInt('<?=$data["request_type"]?>');

if(request_typex === 0){
  service_view.innerHTML = atob(BarangayClearance);
}
else if(request_typex === 1){
  service_view.innerHTML = atob(CertificateResidency);
}
else if(request_typex === 2){
  service_view.innerHTML = atob(CertificateIndigency);
}
else if(request_typex === 3){
  service_view.innerHTML = atob(OathOfUndertaking);
}
else if(request_typex === 4){
  service_view.innerHTML = atob(BusinessClearance);
}
else if(request_typex === 5){
  service_view.innerHTML = atob(BarangayID);
}

var inputFields = document.querySelectorAll('input, textarea');
inputFields.forEach(function(input) {
    input.setAttribute('readonly', true);
});

</script>
