<!DOCTYPE html>
<html>
<head>
    <title>Request Status Update</title>
</head>
<body>
    <p>Hello,</p>

    <p>Your request "{{ $requestName }}" has been {{ $status }}.</p>

    @if ($status === 'declined' && $reason)
        <p>Reason for decline: {{ $reason }}</p>
    @endif

    <p>From {{ config('app.name') }}</p>
</body>
</html>
