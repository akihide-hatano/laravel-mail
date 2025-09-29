<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInboxRequest;
use App\Models\MailInbox;

class InboxController extends Controller
{
    public function index()
    {
        $items = MailInbox::query()->latest('received_at')->latest()->paginate(10);
        return view('mail.inbox', compact('items'));
    }

    // 疑似受信
    public function receive(StoreInboxRequest $request)
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

    public function toggleRead(MailInbox $inbox)
    {
        $inbox->is_read = ! $inbox->is_read;
        $inbox->save();

        return back()->with('ok', '既読フラグを切り替えました');
    }
}
