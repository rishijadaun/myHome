Hello {{ $userName ?: 'there' }},

We received a request to reset the password for your StayNest account.

Your 6-Digit Password Reset Code is:
{{ $otp }}

This code is valid for 15 minutes only.

Security Reminder: Never share this code with anyone. If you did not request this password reset, your account remains secure and you can safely ignore this email.

Warm regards,
Team StayNest
{{ config('app.url') }}
Support: {{ config('mail.from.address') }}
