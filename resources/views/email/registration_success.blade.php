<!DOCTYPE html>
<html>
<head>
    <title>Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logo {
            max-width: 200px; /* Adjust logo size as needed */
            height: auto;
            margin: 0 auto;
        }
        .system-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .message {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- <img src="{{ asset('sys_logo/logo.png') }}" alt="System Logo" class="logo"> --}}
        <div class="system-name">Barangay Tres Cruses Information Management System</div>
        <p class="message">Mabuhay {{-- $name --}}!</p>
        <p class="message">You have successfully registered to our system. Please wait to verify your account by our staff.</p>
        <p class="message">Thank you for registering with us!</p>
    </div>
</body>
</html>
