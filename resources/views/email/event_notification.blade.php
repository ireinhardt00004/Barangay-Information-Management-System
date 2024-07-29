<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Event Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
        }
        p {
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title }}</h1>
        <p><strong>Description:</strong> {{ $description }}</p>
        <p><strong>Type:</strong> {{ $type }}</p>
        <p><strong>Start:</strong> {{ \Carbon\Carbon::parse($start_datetime)->toDayDateTimeString() }}</p>
        <p><strong>End:</strong> {{ \Carbon\Carbon::parse($end_datetime)->toDayDateTimeString() }}</p>
        <p>Thank you for your attention.</p>
        <p>Sincerely,</p>
        <p>{{ config('app.name') }}</p>
       
    </div>
</body>
</html>
