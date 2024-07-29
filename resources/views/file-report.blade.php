@extends('layouts.app')

@section('content')
@include('layouts.navs')
<div class="semi-body d-flex align-items-center min-vh-100">
  <div class="container w-100 bg-white p-4 rounded shadow">
    <h2 class="mb-4">Report Request</h2>
    <form method="POST" action="{{ route('sendreport.request') }}" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-group">
            <label for="fullname" class="form-label">Full Name</label>
            <input class="form-control" id="fullname" name="fullname" type="text" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input class="form-control" id="email" name="email" type="email" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="contact_num" class="form-label">Contact Number</label>
            <input class="form-control" id="contact_num" name="contact_num" type="text" maxlength="11" required>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="report_photo" class="form-label">Upload Photo (Optional)</label>
            <input class="form-control" id="report_photo" name="report_photo" type="file" accept="image/*">
          </div>
        </div>

        <div class="col-12">
          <div class="form-group">
            <label for="issue" class="form-label">Issue</label>
            <textarea style="height:300px;" class="form-control" id="issue" name="issue" required placeholder="Enter issue..."></textarea>
          </div>
        </div>

        <div class="col-12 text-center mt-3">
          <input class="btn btn-success" type="submit" value="Submit">
        </div>
      </div>
    </form>
  </div>
</div>


<style>
:root {
  --cover-op: 0.8;
}
body {
  background-color: rgba(238, 225, 180, 0.9);
}
nav {
  background-color: rgba(0, 0, 0, 0.8);
}
.semi-body {
  height: 100%;
}
.container {
  max-width: 800px;
  margin: auto;
}
form .form-group {
  margin-bottom: 1rem;
}
form .form-label {
  font-weight: bold;
}
form .form-control {
  border-radius: 0.25rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}
form .form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showAlert(type, message) {
  Swal.fire({
    icon: type,
    title: type === 'success' ? 'Success' : 'Error',
    text: message,
    confirmButtonText: 'OK'
  });
}

@if(session('alert'))
  const alert = @json(session('alert'));
  showAlert(alert.type, alert.message);
@endif
</script>


@section('title', 'Report Request')
