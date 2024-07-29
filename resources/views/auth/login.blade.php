@extends('layouts.app')

@section('content')
<div class="wrapper1 h-100 d-flex justify-content-center">
    <div class="container-fluid">
        <div class="row h-100">
            <div class="left-login col-12 col-sm-6 d-flex align-items-center justify-content-center">
                <a href="/" title="Return to Home page"><img height="160" src="{{ asset('assets/imgs/logo.png') }}"></a>
            </div>
            <div class="right-login col-12 col-sm-6 d-flex align-items-center justify-content-center">
                <div class="w-100">
                    <div class="d-md-none mb-4 d-sm-none d-flex align-items-center justify-content-center">
                        <a href="/" ><img class="" height="160" src="{{ asset('assets/imgs/logo.png') }}" ></a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="r-login-title">
                            <h2 class="ms-2"><b>Barangay Login<b></h2>
                        </div>
                    </div>
                    {{-- ERROR CHECKER --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="d-flex justify-content-center mt-3">
                        <form id="login-form" action="{{ route('login') }}" method="POST" class="cstm-form">
                            @csrf
                            <div class="cstm-ph-form">
                                <input type="email" name="email" id="emailbox" placeholder="Your email" required autofocus>
                            </div>
                            <div class="cstm-ph-form d-flex">
                                <input type="password" name="password" placeholder="Password" required>
                                <i onclick="toggle_passwd();" class="eye fa-solid fa-eye-slash"></i>
                            </div>
                            <div class="s-c-box d-flex align-items-center justify-content-center">
                                <div class="d-flex mx-auto ms-3">
                                    <input type="checkbox" checked>
                                    <p>Remember me</p>
                                </div>
                                <div class="mx-auto me-2">
                                    <input class="btn" type="submit" value="Login">
                                </div>
                            </div>
                            <div class="mt-4 d-flex flex-row text-center">
                                @if (Route::has('forgotmy.password'))
                                <a id="forgot-password-link" class="mx-auto" href="{{ route('forgotmy.password') }}">Forgot password?</a>
                                @endif
                                <a href="#" id="register-link" class="mx-auto">Register</a>
                            </div>
                        </form>
                        <form id="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="cstm-form" style="display: none;">
                            @csrf
                            <div class="cstm-ph-form">
                                <input type="text" name="fname" placeholder="First Name" required>
                            </div>
                            <div class="cstm-ph-form">
                                <input type="text" name="lname" placeholder="Last Name" required>
                            </div>
                            <div class="cstm-ph-form">
                                <input type="text" name="middlename" placeholder="Middle Name (if applicable)">
                            </div>
                            <div class="cstm-ph-form">
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="cstm-ph-form">
                                <label for="valid_id" class="custom-label">Upload Valid ID:</label>
                                <input type="file" name="valid_id" id="valid_id" required accept="image/*">
                            </div>
                            <div class="cstm-ph-form d-flex">
                                <input type="password" name="password" placeholder="Password (min length 8)" minlength="8" required>
                            </div>
                            <div class="cstm-ph-form d-flex">
                                <input type="password" name="password_confirmation" placeholder="Confirm Password" minlength="8" required>
                            </div>
                            <div class="s-c-box d-flex align-items-center justify-content-center">
                                <div class="d-flex mx-auto ms-1">
                                    <p id="showpasswd" style="cursor:pointer;user-select:none;"><i class="eye fa-solid fa-eye-slash"></i>Show password</p>
                                </div>
                                <div class="mx-auto me-2">
                                    <input class="btn" type="submit" value="Register">
                                </div>
                            </div>
                            <div class="mt-4 d-flex flex-column text-center">
                                <a href="#" id="login-link">Already have an account? Login</a>
                            </div>
                        </form>
                    </div>
                    <style>
                        ::placeholder {
                            color: rgb(238, 216, 100)!important;
                            opacity: 1; /* Firefox */
                        }
                        .custom-label {
                            color: rgb(238, 216, 137)!important;
                            font-weight: bold;
                            display: block;
                            margin-bottom: 5px;
                        }
                        #valid_id {
                            border: 1px solid rgb(238, 216, 137);
                            border-radius: 4px;
                            padding: 5px;
                            background-color: rgb(238, 216, 137)!important;
                        }
                        .cstm-form{
                            width:100%;
                            max-width:300px;
                        }
                        .cstm-ph-form{
                            margin:10px 0;
                            width:100%;
                            border-bottom:3px solid rgb(238, 216, 137)!important;
                        }
                        .cstm-form a{
                            color: rgb(238, 216, 137)!important;
                            font-size:14px;
                        }
                        .cstm-ph-form input{
                            width: 100%;
                            outline:none;
                            border:none;
                        }
                        .cstm-form input[type=checkbox]{
                            border:1px solid rgb(238, 216, 137);
                            transform:scale(1.3);
                            accent-color: rgb(238, 216, 137);
                        }
                        .cstm-form input[type=submit]{
                            background-color: rgb(238, 216, 137)!important;
                            border-radius:8px;
                            color:white;
                        }
                        .eye{
                            padding:2px;
                            color: rgb(238, 216, 137);
                        }
                        .eye:hover{
                            cursor:pointer;
                        }
                        .cstm-form p{
                            margin: 9px;
                            font-size:14px;
                            color: rgb(238, 216, 137);
                        }
                        @media(max-width: 576px){
                            .left-login{
                                display: none!important;
                            }
                        }
                        /* Styled Error Message */
                        .alert {
                            padding: 10px;
                            margin-bottom: 20px;
                            border: 1px solid transparent;
                            border-radius: 4px;
                            width: 90%;
                            max-width: 400px;
                            text-align: center;
                            margin-left: auto;
                            margin-right: auto;
                        }
                        .alert-danger {
                            color: #721c24;
                            background-color: #f8d7da;
                            border-color: #f5c6cb;
                        }
                        .alert-danger ul {
                            margin: 0;
                            padding: 0;
                            list-style: none;
                        }
                        .alert-danger li {
                            margin-bottom: 5px;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script>
    function toggle_passwd(){
        let passwdField = document.querySelector('input[name="password"]');
        let eyeIcon = document.querySelector('.eye');
        if (passwdField.type === "password") {
            passwdField.type = "text";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            passwdField.type = "password";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    }

    document.querySelector('#register-link').addEventListener('click', function() {
        document.querySelector('#login-form').style.display = 'none';
        document.querySelector('#register-form').style.display = 'block';
    });

    document.querySelector('#login-link').addEventListener('click', function() {
        document.querySelector('#login-form').style.display = 'block';
        document.querySelector('#register-form').style.display = 'none';
    });
</script>
@endsection
