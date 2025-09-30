<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">新規メール作成</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow sm:rounded-lg p-6 space-y-6">

        @if(session('ok'))
          <div class="p-3 bg-green-50 text-green-700 rounded">{{ session('ok') }}</div>
        @endif

        <form method="POST" action="{{ route('mail.outbox.store') }}" class="space-y-4">
          @csrf

          <div>
            <label class="block text-sm font-medium mb-1">宛先（To）<span class="text-red-500">*</span></label>
            <input type="email" name="to_email" value="{{ old('to_email') }}" class="w-full border rounded p-2">
            @error('to_email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">件名</label>
            <input type="text" name="subject" value="{{ old('subject') }}" class="w-full border rounded p-2">
            @error('subject') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">本文</label>
            <textarea name="body" rows="8" class="w-full border rounded p-2">{{ old('body') }}</textarea>
            @error('body') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="flex gap-2">
            <a href="{{ route('mail.outbox.index') }}" class="px-4 py-2 border rounded">戻る</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">送信キューに登録</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</x-app-layout>
