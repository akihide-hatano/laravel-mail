<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">ダッシュボード</h2>
            <a href="{{ route('mail.outbox.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-500">
                新規作成
            </a>
        </div>
    </x-slot>

      <div class="p-6">
    {{-- ページ本体 --}}
  </div>

</x-app-layout>