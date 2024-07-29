@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">
                    <a href="{{ route('announcements.indexpage') }}" style="color: black; text-decoration:none;">Announcement</a> | <span style="font-size:22px;">Create Announcement</span>
                </h1>
            </div>
            <hr style="border:1px solid black;">

            <div class="shadow d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                <div class="content-wrap container mb-2">
                    <div class="form-wrap p-2">
                        <div class="table-wrap d-flex justify-content-center">
                            <form class="container" id="announcement-form" method="POST" enctype="multipart/form-data" action="{{ route('announcements.store') }}">
                                @csrf
                                <div class="d-lg-flex d-md-flex container">
                                    <div class="text-center">
                                        <img class="rounded w-100" id="prev-img-event" height="250px" src="{{ asset('assets/imgs_uploads/bg-img2.jpg') }}">
                                        <label for="imageFilez" class="w-50 mt-2 btn btn-outline-primary">
                                            <input onchange="sel_img('prev-img-event');" class="form-control d-none" type="file" id="imageFilez" name="imageFilez" accept="image/gif, image/png, image/jpeg" required>
                                            Change Image
                                        </label> 
                                    </div>
                                    <div class="m-1 container">
                                        <div class="input-group mb-1">
                                            <span class="input-group-text" id="basic-title">Title: </span>
                                            <input class="form-control" aria-label="Title" aria-describedby="basic-title" type="text" name="event-title" placeholder="My title.." autocomplete="off" value="{{ old('event-title') }}">
                                        </div>
                                        @error('event-title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <textarea id="markdown-editor-container" style="min-height:300px;" class="w-100 form-control mt-1" name="event-content" autocomplete="off" placeholder="Content..">{{ old('event-content') }}</textarea>
                                        @error('event-content')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <input class="w-50 btn btn-outline-success mt-2" type="submit" value="Create Announcement">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
             </div>
    </main>
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
    .page-name:focus{
        box-shadow:none!important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<script>
function sel_img(eid){
    var file_path = document.getElementById("imageFilez");
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').pop().toLowerCase();
    if(['jpg', 'gif', 'png', 'jpeg'].includes(f_ext)){
        preview.src = URL.createObjectURL(file_path.files[0]);
        preview.classList.remove('cstm-hidden');
    }
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

document.getElementById('announcement-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    
    fetch('{{ route('announcements.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There was an error processing your request. Please try again later.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'There was an error processing your request. Please try again later.',
            confirmButtonText: 'OK'
        });
        console.error('Error:', error);
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title', 'Create Announcement')
