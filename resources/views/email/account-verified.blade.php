<!DOCTYPE html>
<html>
<head>
    <title>Account Verified</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: white;
            background-color: #3490dc;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Your Account has been Verified</h1>
    <p>Mabuhay! {{ $user->fname }},</p>
    <p>Your account has been verified and approved by our staff. You can now log in to the system.</p>
    <a href="{{ route('login') }}" class="button">Log In</a>
    <p>Regards,<br>{{ config('app.name') }}</p>
</body>
</html>
