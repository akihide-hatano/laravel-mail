<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">ダッシュボード</h2>
            <a href="{{ route('mail.outbox.create') }}" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-500">
                新規作成
            </a>
        </div>
    </x-slot>

 <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- 送信BOX 直近5件 --}}
    <div class="bg-white rounded shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold">送信BOX（最新5件）</h3>
        <a href="{{ route('mail.outbox.index') }}" class="text-sm text-blue-600 hover:underline">もっと見る</a>
      </div>
      @if($outboxRecent->isEmpty())
        <div class="text-gray-500 text-sm">データがありません</div>
      @else
        <table class="w-full text-sm">
          <thead class="text-gray-500">
            <tr><th class="text-left py-1">宛先</th><th class="text-left py-1">件名</th><th class="text-left py-1">状態</th></tr>
          </thead>
          <tbody>
          @foreach($outboxRecent as $row)
            <tr class="border-t">
              <td class="py-1 pr-2 truncate">{{ $row->to_email }}</td>
              <td class="py-1 pr-2 truncate">
                <a href="{{ route('mail.outbox.show',$row) }}" class="hover:underline">
                  {{ $row->subject ?? '（件名なし）' }}
                </a>
              </td>
              <td class="py-1">
                <span class="{{ $row->status->colorClasses() }}">{{ $row->status->label() }}</span>
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      @endif
    </div>

    {{-- 受信BOX 直近5件 --}}
    <div class="bg-white rounded shadow p-4">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold">受信BOX（最新5件）</h3>
        <a href="{{ route('mail.inbox.index') }}" class="text-sm text-blue-600 hover:underline">もっと見る</a>
      </div>
      @if($inboxRecent->isEmpty())
        <div class="text-gray-500 text-sm">データがありません</div>
      @else
        <table class="w-full text-sm">
          <thead class="text-gray-500">
            <tr><th class="text-left py-1">差出人</th><th class="text-left py-1">件名</th><th class="text-left py-1">受信日時</th></tr>
          </thead>
          <tbody>
          @foreach($inboxRecent as $row)
            <tr class="border-t">
              <td class="py-1 pr-2 truncate">{{ $row->from_email }}</td>
              <td class="py-1 pr-2 truncate">
                <a href="{{ route('mail.inbox.show',$row) }}" class="hover:underline">
                  {{ $row->subject ?? '（件名なし）' }}
                </a>
              </td>
              <td class="py-1 text-gray-600">{{ optional($row->received_at)->format('Y-m-d H:i') }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

</x-app-layout>