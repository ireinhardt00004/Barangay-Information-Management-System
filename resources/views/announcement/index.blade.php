@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Announcements List</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="content-wrap container mb-2">
                <div class="form-wrap p-2">
                    <h2>Announcement
                        <a href="{{ route('announcements.create') }}" class="btn btn-sm btn-outline-primary rounded-pill mb-2">
                            <i class="rounded-circle fa-regular fa-plus"></i> Create new
                        </a>
                    </h2>
                    <div class="table-wrap container d-flex justify-content-center">
                        @if($announcements->isNotEmpty())
                            <table class="table table-light table-striped table-bordered mt-2 rounded" style="font-size:18px;">
                                <thead class="text-center mb-5">
                                    <tr>
                                        <th class="text-muted">Cover</th>
                                        <th class="text-muted">Title</th>
                                        <th class="text-muted">Content</th>
                                        <th class="text-muted" colspan="2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcement)
                                        @php
                                            $id = $announcement->id;
                                            $img = base64_decode($announcement->cover);  // Decode base64 cover
                                            $name = base64_decode($announcement->title);  // Decode base64 title
                                            $desc = base64_decode($announcement->content);  // Decode base64 content
                                            $desc = strlen($desc) > 65 ? substr($desc, 0, 65) . "..." : $desc;
                                        @endphp
                                        <tr id="{{ $id }}">
                                            <td><img height="70" class="rounded" width="70" src="{{ asset('announcement_img/' . $img) }}"></td>
                                            <td>{{ $name }}</td>
                                            <td>{{ $desc }}</td>
                                            <td><a class="btn btn-outline-primary w-100" href="{{ route('announcements.edit', $id) }}">Edit</a></td>
                                            <td><a class="btn btn-outline-danger w-100" href="#" onclick="delete_l('announcement', '{{ $id }}');">Delete</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No announcements found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function delete_l(type, id) {
        Swal.fire({
            title: 'Do you confirm to delete this?',
            html: `
            <form id="delete-form" method="post" enctype="multipart/form-data" action="{{ route('announcements.destroy') }}">
                @csrf
                <input type="hidden" name="type" value="` + type + `">
                <input type="hidden" name="id" value="` + id + `">
                <input class="btn btn-outline-danger m-3" type="submit" value="I Confirm">
                <input class="btn btn-secondary m-3" type="button" onclick="Swal.close();" value="Cancel">
            </form>
            `,
            showConfirmButton: false,
            onOpen: () => {
                document.getElementById('delete-form').addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevent default form submission
                    
                    let formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toastify({
                                text: data.message,
                                backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                                className: "info",
                                duration: 3000
                            }).showToast();
                            window.location.reload();
                        } else {
                            Toastify({
                                text: data.message || 'There was an issue deleting the announcement.',
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                className: "info",
                                duration: 3000
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        Toastify({
                            text: 'An error occurred while deleting.',
                            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            className: "info",
                            duration: 3000
                        }).showToast();
                    });
                });
            }
        });
    }

    // Check if there are flash messages and display them using Toastify
    @if (session('success'))
        Toastify({
            text: "{{ session('success') }}",
            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
            className: "info",
            duration: 3000
        }).showToast();
    @elseif (session('error'))
        Toastify({
            text: "{{ session('error') }}",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
            className: "info",
            duration: 3000
        }).showToast();
    @endif
</script>

<!-- Stylesheets and Scripts -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title', 'Announcements List')
