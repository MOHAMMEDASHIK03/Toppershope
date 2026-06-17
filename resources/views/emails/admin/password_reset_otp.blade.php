<x-mail::message>
# Admin password reset

You requested to reset your **Topper's Hope** admin console password.

Your one-time verification code is:

<x-mail::panel>
<div style="font-size: 28px; font-weight: 700; letter-spacing: 0.35em; text-align: center;">{{ $otp }}</div>
</x-mail::panel>

This code expires in **{{ $expiresMinutes }} minutes**. Do not share it with anyone.

If you did not request this, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
