<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">送信BOX</h2></x-slot>

    <div class="max-w-6xl mx-auto p-4 space-y-6">

        {{-- キュー登録フォーム --}}
        <form method="POST" action="{{ route('mail.outbox.store') }}" class="space-y-3">
            @csrf
            <div>
                <x-input-label for="to_email" value="宛先メール" />
                <x-text-input id="to_email" name="to_email" type="email" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('to_email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="subject" value="件名" />
                <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="body" value="本文" />
                <textarea id="body" name="body" rows="4" class="mt-1 block w-full border rounded"></textarea>
            </div>
            <x-primary-button>キュー登録</x-primary-button>
        </form>

        {{-- 検索 --}}
        <form method="GET" action="{{ route('mail.outbox.index') }}" class="flex gap-2 items-center">
            <x-text-input name="s" value="{{ request('s') }}" placeholder="件名 or 宛先を検索" />
            <x-secondary-button>検索</x-secondary-button>
        </form>

        {{-- 一括削除 --}}
        <form id ="bulkForm" method="POST" action="{{ route('mail.outbox.bulk-destroy') }}">
            @csrf @method('DELETE')
        </form>

            <div class="mt-3 overflow-x-auto">
                <table class="min-w-full text-sm table-fixed">
                    <thead>
                        <tr>
                            <th class="p-2 w-10"><input type="checkbox" id="checkAll"></th>
                            <th class="p-2 w-16">ID</th>
                            <th class="p-2 w-[18rem]">宛先</th>
                            <th class="p-2 w-[24rem]">件名</th>
                            <th class="p-2 w-28">ステータス</th>  {{-- ★ ここに幅 --}}
                            <th class="p-2 w-40 text-center">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($items->isEmpty()){
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                メールがありません
                            </td>
                        </tr>
                    }
                    @else
                    @foreach($items as $row)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2 whitespace-nowrap">
                            <input form ="bulkForm" type="checkbox" name="ids[]" value="{{ $row->id }}" class="row-check">
                        </td>

                        {{-- IDセル丸ごとクリック可能 --}}
                        <td class="p-0">
                            <a href="{{ route('mail.outbox.show', $row) }}"
                            class="block px-2 py-2">
                                {{ $row->id }}
                            </a>
                        </td>

                        {{-- 宛先セルもクリック可能 --}}
                        <td class="p-0 w-[18rem]">
                            <a href="{{ route('mail.outbox.show', $row) }}"
                            class="block px-2 py-2 truncate">
                                {{ $row->to_email }}
                            </a>
                        </td>

                        {{-- 件名セルもクリック可能 --}}
                        <td class="p-0 w-[24rem]">
                            <a href="{{ route('mail.outbox.show', $row) }}"
                            class="block px-2 py-2 truncate">
                                {{ $row->subject }}
                            </a>
                        </td>

                        <td class="p-2 w-28"> {{-- ★ TH と同じ幅 --}}
                            <a href="{{ route('mail.outbox.show',$row) }}"
                            class="inline-flex items-center justify-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 whitespace-nowrap {{ $row->status->colorClasses() }}">
                            {{ $row->status->label() }}
                            </a>
                        </td>

                        <td class="p-2 whitespace-nowrap text-right w-40">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('mail.outbox.show',$row) }}"
                                class="inline-flex items-center px-3 py-2 text-sm rounded bg-blue-600 text-white hover:bg-blue-500">
                                    詳細
                                </a>
                                @if($row->status === $draft)
                                <a href="{{ route('mail.outbox.edit',$row) }}" class="inline-flex items-center px-3 py-2 text-sm rounded border hover:bg-gray-100">
                                    編集
                                </a>
                                @endif
                                <form method="POST" action="{{ route('mail.outbox.destroy', $row )}}" class="inline-block"
                                    onsubmit="return confirm('削除しますか？');">
                                    @csrf @method('DELETE')
                                    <x-danger-button class="px-3 py-2 text-sm">削除</x-danger-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @endif
                    </tbody>
                </table>
                <div class="mt-3 flex items-center justify-between">
                <div>{{ $items->links() }}</div>
                {{-- 一括削除の送信ボタン（bulkForm を submit） --}}
                <x-danger-button type="submit" form="bulkForm" class="px-3 py-2 text-sm">
                    選択削除
                </x-danger-button>
                </div>
        </div>

    <script>
        const all = document.getElementById('checkAll');
        const rows = document.querySelectorAll('.row-check');
        all?.addEventListener('change', e => rows.forEach(r => r.checked = all.checked));
    </script>
</x-app-layout>
