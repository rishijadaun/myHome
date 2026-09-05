Hello {{ $userName ?: 'there' }},

We received a request to reset the password for your SpaceSeeks account.

Your 6-digit password reset verification code is:
{{ $otp }}

This code is valid for 15 minutes.

Security Reminder: Never share this code with anyone. If you did not request a password reset, you can safely ignore this email.

Warm regards,
Team SpaceSeeks
{{ config('app.url') }}
Support: {{ config('mail.from.address') }}
