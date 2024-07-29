@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">
                    <a href="{{ route('cmspages.index') }}" style="color: black; text-decoration:none;">CMS - Page</a> | <span style="font-size:22px;">Edit Page</span>
                </h1>
            </div>
            <hr style="border:1px solid black;">

            <div class="shadow d-flex p-2 rounded" style="background-color:rgb(255,255,255,0.4);">
                <div class="d-flex align-items-center justify-content-center mx-2">
                    <label for="page_name">Page name: </label>
                </div>
                <div class="">
                    <input class="form-control page-name" id="page_name" autofocus type="text" value="{{ $page_name }}" required>
                </div>
            </div>
            <div style="font-size:16px;" class="shadow rounded">
                <textarea id="markdown-editor-container" placeholder="Aa...">{{ $content }}</textarea>
            </div>
            <button class="shadow mt-2 mb-2 btn btn-success" onclick="saveMD();">Save Changes</button>
        </div>
    </main>
</div>

<style>
    .search-btn input {
        outline: none;
        border-radius: 5px;
        border: 1px solid rgb(0,0,0,0.4);
    }
    .cstm-hover:hover {
        background-color: rgb(0,0,0,0.3);
        border-radius: 10px;
    }
    .act-btn {
        font-size: 18px;
    }
    .page-name:focus {
        box-shadow: none !important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<script>
    const page = document.getElementById("page_name");

    function toast(msg, time, bgColor) {
        Toastify({
            text: msg,
            duration: time,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            style: {
                background: bgColor,
            },
        }).showToast();
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

    function saveMD() {
        var markdownContent = simplemde.value();
        fetch(`{{ route('cmspages.update') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: `save_md_changes=&id={{ $id }}&mdcont=${btoa(markdownContent)}&page_name=${btoa(page.value)}`,
        })
        .then(response => response.text())
        .then(data => {
            const res = data.trim().split("\n");
            const last = res[res.length - 1];
            if (last == 'true') {
                location.href = `{{ route('cmspages.index') }}`;
            } else if (last == 'exists') {
                toast('Error: Page name already exists!', '2000', 'rgb(255,0,0,0.5)');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    page.onkeydown = (e) => {
        const na = ['.', "'", '"', '/', ',', '+', '|', '~', '`', '^', '<', '>', '(', ')', '{', '}', '[', ']', ';', ':', '=', '*', '%', '$', '#', '@', '!', ' ', '\\'];
        const nak = ['v'];
        if (e.ctrlKey && nak.includes(e.key.toLowerCase())) {
            e.preventDefault();
        }
        if (na.includes(e.key.toString())) {
            toast('[Error]: Page name must not contain that symbol!', '2000', 'rgb(255,0,0,0.6)');
            e.preventDefault();
        }
    }
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
@section('title', 'Edit CMS Page')
