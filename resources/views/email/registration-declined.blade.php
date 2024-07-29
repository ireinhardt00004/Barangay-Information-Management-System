<!DOCTYPE html>
<html>
<head>
    <title>Registration Declined</title>
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
    <h1>Registration Declined</h1>
    <p>Dear {{ $user->fname }},</p>
    <p>We regret to inform you that your registration has been declined. Please contact the Administrator for more details or try registering again.</p>
    <p>Thank you.</p>
    <p>Regards,<br>{{ config('app.name') }}</p>
</body>
</html>
