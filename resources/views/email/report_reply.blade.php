<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reply to Your Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .content {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reply to Your Report</h2>
        <p>Dear {{ $fullname }},</p> 
        <p>Thank you for submitting your report. We would like to inform you that:</p>
        <div class="content">
            {!! nl2br(e($content)) !!}
        </div>
        <p>Thank you for your patience and cooperation.</p>
        <p>Best regards,</p>
        <p>{{ config('app.name') }}</p>
    </div>
</body>
</html>
