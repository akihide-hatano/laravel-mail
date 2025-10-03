<x-mail::message>
# {{ $outbox->subject -> '(件名なし)'}}
{!! nl2br(e($outbox->body ?? '（本文なし）')) !!}

<x-mail::panel>
宛先：{{ $outbox->to_email }}
@isset($outbox->sent_at)
送信日時：{{ $outbox->sent_at->format('Y-m-d H:i') }}
@endisset
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
