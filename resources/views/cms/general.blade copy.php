@extends('layouts.app') <!-- Adjust according to your layout -->
@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">CMS-General</span></h1>
            </div>
            <hr style="border:1px solid black;">
            
        <div class="mt-2">
            <h3>Landing page theme</h3>
            <hr>
        </div>
        <form id="generalForm" action="{{ route('cms-general.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3 container row">
                @php
                // Decode base64 values from the $generalConfig collection
                $theme = $generalConfig->theme ?? [];
                $primaryColor = isset($theme['primary']) ? base64_decode($theme['primary']) : '#000000';
                $bgColor = isset($theme['bg']) ? base64_decode($theme['bg']) : '#ffffff';
                $textColor = isset($theme['text']) ? base64_decode($theme['text']) : '#000000';

                // Extract and decode emergency contact information
                $emContacts = $generalConfig->em_contacts ?? [];
                $emCityHall = isset($emContacts['city_hall']) ? base64_decode($emContacts['city_hall']) : '';
                $emPolice = isset($emContacts['police']) ? base64_decode($emContacts['police']) : '';
                @endphp
        
                <div class="col-4 mt-2">
                    <div class="justify-content-center">
                        <label>Primary:</label>
                        <input type="color" class="form-control form-control-color" value="{{ $primaryColor }}" name="theme[primary]" title="Choose a color">
                    </div>
                </div>
                <div class="col-4 mt-2">
                    <div class="justify-content-center">
                        <label>Background:</label>
                        <input type="color" class="form-control form-control-color" value="{{ $bgColor }}" name="theme[bg]" title="Choose a color">
                    </div>
                </div>
                <div class="col-4 mt-2">
                    <div class="justify-content-center">
                        <label>Texts:</label>
                        <input type="color" class="form-control form-control-color" value="{{ $textColor }}" name="theme[text]" title="Choose a color">
                    </div>
                </div>
            </div>
        
            <div class="mt-2">
                <h3>Emergency Contact</h3>
                <hr>
            </div>
        
            <div class="row">
                <div class="col-6">
                    <div class="">
                        <label>City Hall #:</label>
                        <input class="form-control" autocomplete="off" name="em_contacts[city_hall]" value="{{ $emCityHall }}" maxlength="11" placeholder="Ex: 09123123123">
                    </div>
                </div>
                <div class="col-6">
                    <div class="">
                        <label>Police Station #:</label>
                    <input class="form-control" autocomplete="off" name="em_contacts[police]" value="{{ $emPolice }}" maxlength="11" placeholder="Ex: 09123123123">
                    </div>
                </div>
            </div>
            
<div class="mt-2">
    <h3>General</h3>
    <hr>
</div>
<div class="row">
    <div class="mx-1 col-md-4">
        <label>Title:</label>
        <input class="form-control" autocomplete="off" name="web_title" value="{{ $generalConfig->title }}" placeholder="example..">
    </div>
    <div class="col-md-4">
        <label>Meta description:</label>
        <textarea class="form-control" autocomplete="off" name="meta_desc" placeholder="example..">{{ $generalConfig->meta_desc }}</textarea>
    </div>
</div>


<div class="mt-3">
    <h3>Home page</h3>
    <hr>
</div>
<div class="row">
    <div class="mx-1 col-md-6">
        <label>Head title:</label>
        <input class="form-control" autocomplete="off" name="head_title" value="{{ $generalConfig->head_title }}" placeholder="example nav..">
    </div>
    <div class="mt-3">
        <h3>About page</h3>
        <hr>
    </div>
    <div class="row">
        <div class="mx-1">
            <label>Title:</label>
            <input class="form-control" autocomplete="off" name="about_title" value="{{ $generalConfig->about_title }}" placeholder="example..">
        </div>
        <div class="mx-1">
            <label>Description:</label>
            <div>
                <textarea class="form-control" name="about_description" placeholder="Example...">{{ $generalConfig->about_desc }}</textarea>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <h3>Service Page</h3>
        <hr>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <input class="form-control" hidden autocomplete="off" name="gcash_no" value="{{ $generalConfig->gcash_no }}" placeholder="Ex: 09123123123">
                <label>Max service request per Member </label>
                <input class="form-control" type="number" value="{{ $generalConfig->max_requests }}" name="service_max_no">
            </div>
            <div class="col-3">
                <label>Fees: </label>
                <input class="form-control" autocomplete="off" name="payment_amt" value="{{ $generalConfig->payment_amt }}" placeholder="Ex: 100">
            </div>
        </div>
        <div class="mt-3 mb-5 mx-1 col-md-2 d-flex justify-content-end" style="float: right;">
            <input class="btn btn-primary btn-lg rounded-pill" id="saveGeneralChanges" value="Save changes">
        </div>
        
        </form>
    </div>
    
    <div class="mt-3">
        <h3>Change Logo</h3>
        <hr>
    </div>
    <div class=" container col-md-2">
        <label></label>
        <a class="btn btn-outline-primary" onclick="new_logo();">Upload Logo</a>
    </div>
    <div class="border mt-1 form-wrap p-2">
        <h4>Home header background</h4>
        <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="new_image();">
            <i class="rounded-circle fa-regular fa-plus"></i> Add image
        </button>
        <div class="table-wrap container d-lg-flex d-md-flex mt-3">
            <div class="row w-100">
                @foreach($homeimgs as $data)
                    @php
                        // Assuming $data->img_path is a base64 encoded string or an image path
                        $img = base64_decode($data->img_path); // Decode base64 if needed
                        $item_id = $data->id;
                    @endphp
                    <section class="m-2 image col-md-4 position-relative d-flex">
                        <button class="position-absolute badge rounded w-25 h-25 btn btn-danger" onclick="del_imgz('{{ $item_id }}');">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        <img class="w-100" src="{{ asset('home_images/' . $img) }}" alt="Home Image">
                    </section>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="container mt-2 border">
        <h4>Home header buttons</h4>
        <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="create_head_btn();">Add new button</button>
        <div class="row">
            <div class="col-md-12">
                <div class="table-div shadow">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Button Name</th>
                                <th>Button Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($headerBtns as $h_btns)
                                @php
                                    $isOutline = ($h_btns['outline'] === 'on') ? 'checked' : '';
                                @endphp
                                <tr>
                                    <td>{{ $h_btns['name'] }}</td>
                                    <td><a href="{{ $h_btns['link'] }}">{{ $h_btns['link'] }}</a></td>
                                    <td style="width:100px;">
                                        <button class="mx-1 btn btn-sm btn-outline-danger" onclick="delete_headerbtn('head_btn', '{{ $h_btns['id'] }}');"><i class="fas fa-trash-alt"></i></button>
                                        {{-- <button class="mx-1 btn btn-sm btn-outline-warning act-btn" onclick="edit_head_btn('{{ $h_btns['name'] }}', '{{ $h_btns['link'] }}', '{{ $isOutline }}', '{{ $h_btns['id'] }}');"><i class="fas fa-edit"></i></button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <h3>Cards</h3>
        <hr>
        <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="new_card();">Add new card</button>
        <div class="row">
            <div class="col-md-12">
                <div class="table-div shadow" style="overflow:scroll;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($homecards as $cdata)
                                @php
                                    $id = $cdata['id'];
                                    $img = base64_decode($cdata['img']);
                                    $title = $cdata['title'];
                                    $link = $cdata['link'];
                                @endphp
                                <tr>
                                    <td width="100"><img class="img-fluid" src="{{ asset('header_cards/' . $img) }}" alt="Card Image"></td>
                                    <td>{{ $title }}</td>
                                    <td><a href="{{ $link }}">{{ $link }}</a></td>
                                    <td style="width:100px;">
                                        <button class="mx-1 btn btn-sm btn-outline-danger" onclick="delete_headercards('cardz', '{{ $id }}');"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <h3>Footer</h3>
            <hr>
            <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="create_footer_btn();">Add new link</button>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-div shadow" style="overflow: scroll;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Gov Links</th>
                                    <th>Official Social Media Account</th>
                                    <th>Contact Us</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($footers as $fdata)
                                    <tr>
                                        <td><a href="{{ $fdata->gov }}">{{ $fdata->gov }}</a></td>
                                        <td><a href="{{ $fdata->social }}">{{ $fdata->social }}</a></td>
                                        <td><a href="{{ $fdata->contact }}">{{ $fdata->contact }}</a></td>
                                        <td style="width:100px;">
                                            <button class="mx-1 btn btn-sm btn-outline-danger" onclick="delete_footerlinkz('cardz', '{{ $fdata->id }}');">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
{{-- 
        <div class="mt-3">
            <h3>System Settings</h3>
            <hr>
            <button class="mb-2 btn btn-sm rounded-pill btn-outline-primary" onclick="create_system_btn();">Add new System Settings</button>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-div shadow" style="overflow: scroll;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Setting</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($systemSettings as $setting)
                                    @php
                                        $id = $setting['id'];
                                        $name = $setting['name'];
                                        $value = $setting['value'];
                                    @endphp
                                    <tr>
                                        <td>{{ $name }}</td>
                                        <td>{{ $value }}</td>
                                        <td style="width:100px;">
                                            <button class="mx-1 btn btn-sm btn-outline-danger" onclick="delete_l('system_settings', '{{ $id }}');"><i class="fas fa-trash-alt"></i> Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
--}}
    </div>
</div> 



<script>
    document.getElementById('saveGeneralChanges').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default form submission
        save_page_change();
    });
    
    function save_page_change() {
        let form = document.getElementById('generalForm');
        let formData = new FormData(form);
    
        fetch('{{ route('cms-general.update') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT' 
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Changes saved successfully!'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while saving changes.'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving changes.'
            });
        });
    }
    </script>    
</div>
</main>
</div>
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />

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
@section('title','General - CMS')
