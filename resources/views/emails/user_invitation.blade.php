<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invitation</title>
</head>
<body>
    <h2>Hello {{ $invitation->name }},</h2>
 
    <p>
        You have been invited by
        <strong>{{ $inviter->name }}</strong>
        ({{ $inviter->getRoleNames()->first() }})
        to join as
        <strong>{{ ucfirst($invitation->user_type) }}</strong>.
    </p>
 
    <p>
        This invitation will expire on:
        <strong>{{ \Carbon\Carbon::parse($invitation->expires_at)->format('d M Y H:i') }}</strong>
    </p>
 
    <p>
        Click the link below to accept the invitation:
    </p>
 
    <p>
        <a href="{{ $frontendUrl }}">
            Activate Your Account
            </a>
    </p>
 
    <br>
 
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>