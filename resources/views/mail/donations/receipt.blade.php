@php
    $amountFormatted = number_format($amount, 2);
@endphp

<div style="font-family: ui-sans-serif, system-ui, -apple-system, Roboto, 'Segoe UI', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji'; line-height:1.5; color:#111827;">
    <h1 style="font-size:20px; margin:0 0 12px;">Thank you for your donation!</h1>

    <p style="margin:0 0 12px;">
        Hi {{ $user->name }},
    </p>

    <p style="margin:0 0 12px;">
        We have received your donation to <strong>{{ $campaign->title }}</strong>.
    </p>

    <ul style="margin:0 0 12px; padding-left:18px;">
        <li><strong>Amount:</strong> {{ $currency }} {{ $amountFormatted }}</li>
        @if(!empty($reference))
            <li><strong>Reference:</strong> {{ $reference }}</li>
        @endif
        <li><strong>Date:</strong> {{ now()->toDayDateTimeString() }}</li>
    </ul>

    <p style="margin:0 0 12px;">Your support helps us move this campaign forward. We truly appreciate it.</p>

    <p style="margin:24px 0 0; color:#6B7280; font-size:12px;">This is an automated confirmation. Please keep it for your records.</p>
</div>
