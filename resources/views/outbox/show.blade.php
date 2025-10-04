<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">送信メール 詳細</h2></x-slot>

  <div class="max-w-3xl mx-auto p-4 space-y-4">
    <div class="bg-white shadow-sm ring-1 ring-gray-400 rounded-lg p-4">
      <table class="w-full text-sm border  border-gray-600">
        <tbody class="border  border-gray-600">
          <tr class="odd:bg-gray-50">
            <th class="w-28 p-2 text-left align-top border  border-gray-200 bg-gray-50">ID</th>
            <td class="p-2 border border-gray-200">{{ $outbox->id }}</td>
          </tr>
          <tr class="odd:bg-gray-50">
            <th class="p-2 text-left align-top border ring-2 ring-gray-200 bg-gray-50">宛先</th>
            <td class="p-2 border border-gray-200">{{ $outbox->to_email }}</td>
          </tr>
          <tr class="odd:bg-gray-50">
            <th class="p-2 text-left align-top border border-gray-200 bg-gray-50">件名</th>
            <td class="p-2 border border-gray-200">{{ $outbox->subject }}</td>
          </tr>
          <tr class="odd:bg-gray-50">
            <th class="p-2 text-left align-top border border-gray-200 bg-gray-50">本文</th>
            <td class="p-2 border border-gray-200 whitespace-pre-wrap">{{ $outbox->body }}</td>
          </tr>
          <tr class="odd:bg-gray-50">
            <th class="p-2 text-left align-top border border-gray-200 bg-gray-50">ステータス</th>
            <td class="p-2 border border-gray-200">
              <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring ring-inset whitespace-nowrap {{ $outbox->status->colorClasses() }}">
                {{ $outbox->status->label() }}
              </span>
            </td>
          </tr>
          <tr class="odd:bg-gray-50">
            <th class="p-2 text-left align-top border border-gray-200 bg-gray-50">登録</th>
            <td class="p-2 border border-gray-200">
              {{ optional($outbox->created_at)->format('Y-m-d H:i') }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="flex gap-2">
      <a href="{{ route('mail.outbox.index') }}"
         class="inline-flex items-center px-3 py-2 rounded ring-1 ring-gray-600 hover:bg-gray-400">
        一覧へ
      </a>

      @if($outbox->status === \App\Enums\OutboxStatus::Draft)
        <a href="{{ route('mail.outbox.edit',$outbox) }}"
           class="inline-flex items-center px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-500">
          編集
        </a>
      @endif

      <form method="POST" action="{{ route('mail.outbox.destroy',$outbox) }}"
            onsubmit="return confirm('削除しますか?')">
        @csrf @method('DELETE')
        <button class="inline-flex items-center px-3 py-2 rounded bg-rose-600 text-white hover:bg-rose-500">
          削除
        </button>
      </form>
    </div>
  </div>
</x-app-layout>
