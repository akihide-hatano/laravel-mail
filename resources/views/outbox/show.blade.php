<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">送信メール 詳細</h2></x-slot>
  <div class="max-w-3xl mx-auto p-4 space-y-4">
    <div class="rounded border p-4">
      <dl class="grid grid-cols-4 gap-2">
        <dt class="font-semibold col-span-1">ID</dt><dd class="col-span-3">{{ $outbox->id }}</dd>
        <dt class="font-semibold col-span-1">宛先</dt><dd class="col-span-3">{{ $outbox->to_email }}</dd>
        <dt class="font-semibold col-span-1">件名</dt><dd class="col-span-3">{{ $outbox->subject }}</dd>
        <dt class="font-semibold col-span-1">本文</dt><dd class="col-span-3 whitespace-pre-wrap">{{ $outbox->body }}</dd>
        <dt class="font-semibold col-span-1">ステータス</dt><dd class="col-span-3 status->colorClasses()">{{ $outbox->status->label() }}</dd>
        <dt class="font-semibold col-span-1">登録</dt><dd class="col-span-3">{{ optional($outbox->created_at)->format('Y-m-d H:i') }}</dd>
      </dl>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('mail.outbox.index') }}" class="underline">← 一覧へ</a>
      <form method="POST" action="{{ route('mail.outbox.destroy', $outbox) }}" onsubmit="return confirm('削除しますか？')">
        @csrf @method('DELETE')
        <button class="text-red-600 underline">削除</button>
      </form>
    </div>
  </div>
</x-app-layout>
