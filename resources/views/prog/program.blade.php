@extends('layouts.app')

@section('title', 'Programs')
@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">Programs</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="content-wrap container mb-2">
                <div class="form-wrap p-2">
                    <h2>Programs</h2>
                    <a href="{{ route('program.create') }}" class="btn btn-sm btn-outline-primary rounded-pill mb-2">
                        <i class="rounded-circle fa-regular fa-plus"></i> Create new
                    </a>
            
                    <div class="container table-responsive">
                        @if($programs->isNotEmpty())
                            <table class="table table-light table-striped table-bordered mt-2 rounded" style="font-size:18px;">
                                <thead class="text-center mb-5">
                                    <tr>
                                        <th class="text-muted">Cover</th>
                                        <th class="text-muted">Title</th>
                                        <th class="text-muted">Program Date</th>
                                        <th class="text-muted">Content</th>
                                        <th class="text-muted" colspan="2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($programs as $program)
                                        @php
                                            $coverImg = base64_decode($program->cover);
                                            $desc = base64_decode($program->content);
                                            $desc = strlen($desc) > 65 ? substr($desc, 0, 65) . "..." : $desc;
                                        @endphp
                                        <tr id="program-{{ $program->id }}">
                                            <td>
                                                <img height="70" class="rounded" width="70" src="{{ asset('program_upload_img/' . $coverImg) }}">
                                            </td>
                                            <td>{{ base64_decode($program->title) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($program->program_date)->format('F d, Y') }}</td>
                                            <td>{{ $desc }}</td>
                                            <td><a class="btn btn-outline-primary w-100" href="{{ route('program.edit', ['id' => $program->id]) }}">Edit</a></td>
                                            <td><button class="btn btn-outline-danger w-100" onclick="confirmDelete({{ $program->id }});">Delete</button></td>
                                        </tr>
                                    @endforeach
                                </tbody>                                
                            </table>
                        @else
                            <p>No programs found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        preConfirm: () => {
            // Create a form and submit it
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('programz.delete', '') }}/${id}`;
            
            let csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            let methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<style>
    th {
        top: 0;
        position: sticky;
        backdrop-filter: blur(6px);
        background-color: rgb(200,200,200,0.2);
        box-shadow: 0px 2px 3px rgb(0,0,0,0.9);
    }
    .form-wrap {
        box-shadow: 0px 0px 5px grey;
        border-radius: 10px;
    }
    .table-wrap {
        max-height: 410px;
        overflow: scroll;
    }
    .content-wrap {
        max-width: 1000px;
    }
    table {
        position: relative;
    }
    tbody a {
        font-weight: bold!important;
    }
</style>

<script>
    const navLinks = document.querySelectorAll('.nav-link');
    const uri = window.location.href;
    const uriParts = uri.split('=');
    const uriValue = uriParts[uriParts.length - 1];
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        const hrefParts = href.split('=');
        const linkValue = hrefParts[hrefParts.length - 1];
    
        if (linkValue === uriValue) {
            link.classList.add('active-nav');
        }
    });
</script>
<script>
    const navLinks = document.querySelectorAll('.nav-link');
    const uri = window.location.href;
    const uriParts = uri.split('=');
    const uriValue = uriParts[uriParts.length - 1];
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        const hrefParts = href.split('=');
        const linkValue = hrefParts[hrefParts.length - 1];
    
        if (linkValue === uriValue) {
            link.classList.add('active-nav');
        }
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
@section('title','List of Programs')
