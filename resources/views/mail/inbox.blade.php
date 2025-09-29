<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">受信BOX</h2></x-slot>

    <div class="max-w-4xl mx-auto p-4 space-y-6">
        @if(session('ok'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-800">
            {{ session('ok') }}
        </div>
    @endif


        {{-- 疑似受信（store） --}}
        <form method="POST" action="{{ route('mail.inbox.store') }}" class="space-y-3">
            @csrf
            <div>
                <x-input-label for="from_email" value="送信元メール" />
                <x-text-input id="from_email" name="from_email" type="email" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('from_email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="subject" value="件名" />
                <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="body" value="本文" />
                <textarea id="body" name="body" rows="4" class="mt-1 block w-full border rounded"></textarea>
            </div>
            <x-primary-button>受信として保存</x-primary-button>
        </form>

        {{-- 検索＆ごみ箱表示切替 --}}
        <form method="GET" action="{{ route('mail.inbox.index') }}" class="flex gap-2 items-center">
            <x-text-input name="s" value="{{ request('s') }}" placeholder="件名 or 差出人を検索" />
            <label class="inline-flex items-center gap-1 text-sm">
                <input type="checkbox" name="withTrashed" value="1" {{ request('withTrashed') ? 'checked' : '' }}>
                ごみ箱も表示
            </label>
            <x-secondary-button>検索</x-secondary-button>
        </form>

        {{-- 一括削除 --}}
        <form method="POST" action="{{ route('mail.inbox.bulk-destroy') }}">
            @csrf @method('DELETE')

            <div class="overflow-x-auto mt-3">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="p-2"><input type="checkbox" id="checkAll"></th>
                            <th>ID</th><th>差出人</th><th>件名</th><th>受信時刻</th><th>既読</th><th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $row)
                        <tr class="border-b {{ $row->trashed() ? 'opacity-50' : '' }}">
                            <td class="p-2">
                                @unless($row->trashed())
                                    <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="row-check">
                                @endunless
                            </td>
                            <td class="p-2">{{ $row->id }}</td>
                            <td class="p-2">{{ $row->from_email }}</td>
                            <td class="p-2">{{ $row->subject }}</td>
                            <td class="p-2">{{ optional($row->received_at)->format('Y-m-d H:i') }}</td>
                            <td class="p-2">{{ $row->is_read ? '既読' : '未読' }}</td>
                            <td class="p-2 flex gap-2">
                                @if(!$row->trashed())
                                    {{-- 既読切替（update） --}}
                                    <form method="POST" action="{{ route('mail.inbox.update', $row) }}">
                                        @csrf @method('PUT')
                                        <x-secondary-button>既読切替</x-secondary-button>
                                    </form>
                                    {{-- 削除 --}}
                                    <form method="POST" action="{{ route('mail.inbox.destroy', $row) }}">
                                        @csrf @method('DELETE')
                                        <x-danger-button>削除</x-danger-button>
                                    </form>
                                @else
                                    {{-- 復元 --}}
                                    <form method="POST" action="{{ route('mail.inbox.restore', $row->id) }}">
                                        @csrf
                                        <x-primary-button>復元</x-primary-button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="mt-3 flex items-center justify-between">
                    <div>{{ $items->links() }}</div>
                    <x-danger-button onclick="this.closest('form').submit()">選択削除</x-danger-button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const all = document.getElementById('checkAll');
        const rows = document.querySelectorAll('.row-check');
        all?.addEventListener('change', e => rows.forEach(r => r.checked = all.checked));
    </script>
</x-app-layout>
