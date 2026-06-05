<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
</head>
<body>
    <p>Dear {{ $approverName }},</p>

    <p>Good day.</p>

    <p>
        We would like to inform you that <a href="{{ $url }}"><strong>{{ $requestTitle }}</strong></a>
        has been reviewed by {{ $requestorName }} and is now awaiting your review and approval.
    </p>

    <p>
        We kindly request your approval at your earliest convenience.
    </p>

    <p>
        Thank you for your time and consideration.
    </p>

    <p>
        Best regards,<br>
        {{ $requestorName }}
    </p>
</body>
</html>