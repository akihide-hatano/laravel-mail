<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreInboxRequest;
use App\Models\MailInbox;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $q = MailInbox::query()->latest('received_at');

        // 検索（件名・差出人）
        if ($s = $request->string('s')->toString()) {
            $q->where(fn($q) =>
                $q->where('subject','like',"%{$s}%")
                ->orWhere('from_email','like',"%{$s}%")
            );
        }

        // ごみ箱も含めて見たい場合（?withTrashed=1）
        if ($request->boolean('withTrashed')) {
            $q->withTrashed();
        }

        $items = $q->paginate(10)->withQueryString();
        return view('inbox.index', compact('items'));
    }

    public function show(MailInbox $inbox){
        // 削除済みも表示したいなら下記に変更：
        // $inbox = MailInbox::withTrashed()->findOrFail($inbox->id);
        return view('indox.show', compact('inbox'));
    }

    // 疑似受信（resourceの store）
    public function store(StoreInboxRequest $request)
    {
        $data = $request->validated();

        MailInbox::create([
            'from_email'  => $data['from_email'],
            'subject'     => $data['subject'] ?? null,
            'body'        => $data['body'] ?? null,
            'received_at' => now(),
            'is_read'     => false,
        ]);

        return back()->with('ok', '受信BOXに保存しました');
    }

    // 既読切替（resourceの update）
    public function update(Request $request, MailInbox $inbox)
    {
        $inbox->is_read = ! $inbox->is_read;
        $inbox->save();
        return back()->with('ok', '既読フラグを切り替えました');
    }

    public function destroy(MailInbox $inbox)
    {
        $inbox->delete(); // SoftDelete
        return back()->with('ok', '削除（ごみ箱へ移動）しました');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            MailInbox::whereIn('id', $ids)->delete();
        }
        return back()->with('ok', '選択したデータを削除しました');
    }

    // 復元（withTrashed→restore）
    public function restore($id)
    {
        $row = MailInbox::withTrashed()->findOrFail($id);
        $row->restore();
        return back()->with('ok', '復元しました');
    }
}