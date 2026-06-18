<x-mail::message>
# Welcome to Topper's Hope!

Hi **{{ $trial->name }}**,

We are thrilled to give you exclusive Trial Access to **{{ $trial->batch->course->title ?? $trial->batch->course->name ?? 'our platform' }} — {{ $trial->batch->name }}**!

You can now log in to our Trial Portal and start learning immediately. Please find your temporary access credentials below:

<x-mail::panel>
**Portal URL:** [{{ url('/trial/login') }}]({{ url('/trial/login') }})  
**Login Email:** {{ $trial->trial_email }}  
**Temporary Password:** {{ $rawPassword }}
</x-mail::panel>

<x-mail::button :url="url('/trial/login')" color="primary">
Start Learning Now
</x-mail::button>

*Note: Your trial access will automatically expire on **{{ $trial->expires_at->format('d M Y, h:i A') }}** (in {{ $trial->daysLeft() }} days). Make the most out of it!*

If you have any questions or need assistance, feel free to reply to this email or reach out to our admission team.

Best Regards,<br>
**Topper's Hope Admission Team**
</x-mail::message>
