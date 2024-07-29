@extends('layouts.app')

@section('content')
@include('layouts.sidebar')

<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">
                    <a href="{{ route('view-reportfiled') }}" style="color: black; text-decoration:none;">Report</a> | <span style="font-size:22px;">View</span>
                </h1>
            </div>
            <hr style="border:1px solid black;">

            <div class="shadow d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                <div class="content-wrap container mb-2">
                    <div class="form-wrap p-2">
                        <div class="table-wrap d-flex justify-content-center">
                            <!-- Report Details -->
                            <div class="container">
                                <div class="d-lg-flex d-md-flex container">
                                    <div class="text-center" style="cursor: pointer;" title="Click to enlarge">
                                        <img 
                                            class="rounded w-100" 
                                            id="prev-img-event" 
                                            height="250px" 
                                            src="{{ $report->report_photo ? asset($report->report_photo) : asset('assets/imgs_uploads/bg-img2.jpg') }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#imageModal"
                                        >
                                    </div>
                                    <div class="m-1 container">
                                        <h3>Report Details</h3>
                                        <p><strong>Full Name:</strong> {{ base64_decode($report->fullname) }}</p>
                                        <p><strong>Email:</strong> {{ base64_decode($report->email) }}</p>
                                        <p><strong>Contact Number:</strong> {{ base64_decode($report->contact_num) }}</p>
                                        <p><strong>Issue:</strong> {{ base64_decode($report->issue) }}</p>
                                        <p><strong>Reported At:</strong> {{ \Carbon\Carbon::parse($report->created_at)->format('F j, Y g:i A') }}</p>

                                        @if($report->status !== "resolved")
                                        <!-- Reply Button -->
                                        <button class="btn btn-primary mt-3" id="reply-btn"><i class="fas fa-reply"></i> Reply</button>
                                        <button type="button" class="btn btn-success mt-3" id="mark-read-btn"><i class="fas fa-check"></i> Mark as Resolve</button>
                                        @else
                                        <!-- Message if resolved -->
                                        <p class="mt-3 text-danger" style="text-transform: uppercase;"><strong>Status: Resolved. You have already sent a reply.</strong></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Report Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img 
                    class="img-fluid" 
                    id="modal-img" 
                    src="{{ $report->report_photo ? asset($report->report_photo) : asset('assets/imgs_uploads/bg-img2.jpg') }}" 
                    alt="Report Photo"
                >
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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
    .page-name:focus {
        box-shadow:none!important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('reply-btn').addEventListener('click', function() {
        Swal.fire({
            title: 'Reply to Report (It will send on their respective email)',
            html: `
                <textarea id="reply-content" required name="content" style="width: 100%; height: 300px;"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Send Reply',
            cancelButtonText: 'Cancel',
            didOpen: () => {
                var simplemde = new SimpleMDE({
                    element: document.getElementById("reply-content"),
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
    
                Swal.getConfirmButton().addEventListener('click', () => {
                    const content = simplemde.value();
                    Swal.fire({
                        title: 'Sending Reply...',
                        text: 'Please wait while we process your request.',
                        didOpen: () => {
                            Swal.showLoading(); // Show the loading spinner
                        }
                    });
    
                    let formData = new FormData();
                    formData.append('content', content);
                    formData.append('report_id', '{{ $report->id }}');
    
                    fetch('{{ route('send-report-reply') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Hide the loading spinner
    
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'There was an issue sending the reply.',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close(); // Hide the loading spinner
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'There was an issue sending the reply.',
                            confirmButtonText: 'OK'
                        });
                    });
                });
            }
        });
    });
    
    document.getElementById('mark-read-btn').addEventListener('click', function() {
    Swal.fire({
        title: 'Marking Report as Read...',
        text: 'Please wait while we process your request.',
        didOpen: () => {
            Swal.showLoading(); // Show the loading spinner
        }
    });

    const reportId = '{{ $report->id }}'; 

    fetch('{{ route('mark-report-as-read', '') }}/' + reportId, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        Swal.close(); 

        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'There was an issue marking the report as read.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.close(); // Hide the loading spinner
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'There was an issue marking the report as read.',
            confirmButtonText: 'OK'
        });
    });
});

    </script>
    

<!-- Stylesheets and Scripts -->
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
@endsection

@section('title', 'View Report')
