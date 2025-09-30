<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      送信メール編集 #{{ $outbox->id }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow sm:rounded-lg p-6">
        <form method="POST" action="{{ route('mail.outbox.update', $outbox) }}">
          @csrf
          @method('PATCH')

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">宛先</label>
            <input type="email" name="to_email" value="{{ old('to_email', $outbox->to_email) }}" class="w-full border rounded p-2">
            @error('to_email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">件名</label>
            <input type="text" name="subject" value="{{ old('subject', $outbox->subject) }}" class="w-full border rounded p-2">
            @error('subject') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium mb-1">本文</label>
            <textarea name="body" rows="6" class="w-full border rounded p-2">{{ old('body', $outbox->body) }}</textarea>
            @error('body') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium mb-1">ステータス</label>
            <select name="status" class="w-full border rounded p-2">
              @foreach (['draft'=>'下書き','queued'=>'キュー','sent'=>'送信済み','failed'=>'失敗'] as $v=>$label)
                <option value="{{ $v }}" @selected(old('status', $outbox->status) === $v)>{{ $label }}</option>
              @endforeach
            </select>
            @error('status') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
          </div>

          <div class="flex gap-2">
            <a href="{{ route('mail.outbox.show', $outbox) }}" class="px-4 py-2 border rounded">キャンセル</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">保存</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
