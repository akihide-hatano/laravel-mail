<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">送信BOX</h2></x-slot>

    <div class="max-w-4xl mx-auto p-4 space-y-6">
        @if(session('ok'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-800">
            {{ session('ok') }}
        </div>
        @endif


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
        <form method="POST" action="{{ route('mail.outbox.bulk-destroy') }}">
            @csrf @method('DELETE')

            <div class="overflow-x-auto mt-3">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr>
                            <th class="p-2"><input type="checkbox" id="checkAll"></th>
                            <th>ID</th><th>宛先</th><th>件名</th><th>ステータス</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $row)
                        <tr class="border-b">
                            <td class="p-2">
                                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="row-check">
                            </td>
                            <td class="p-2">{{ $row->id }}</td>
                            <td class="p-2">{{ $row->to_email }}</td>
                            <td class="p-2">{{ $row->subject }}</td>
                            <td class="p-2">{{ $row->status }}</td>
                            <td class="p-2">
                                <form method="POST" action="{{ route('mail.outbox.destroy', $row) }}">
                                    @csrf @method('DELETE')
                                    <x-danger-button>削除</x-danger-button>
                                </form>
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
