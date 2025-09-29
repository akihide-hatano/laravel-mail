<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutboxRequest;
use App\Models\MailOutbox;
use Illuminate\Http\Request;

class OutboxController extends Controller
{
    public function index(){
        $items = MailOutbox::query()->latest()->paginate(10);
        return view('mail.outbox',compact('items'));
    }

    public function store(StoreOutboxRequest $request){

        $data = $request->validated();

        MailOutbox::create([
            'to_email'  => $data['to_email'],
            'subject'   => $data['subject'] ?? null,
            'body'      => $data['body'] ?? null,
            'status'    => 'queued',
            'queued_at' => now(),
        ]);

        // まずは保存のみ（送信は後で queue を差し込む）
        return back()->with('ok', '送信キューに登録しました');
    }

    public function destroy(MailOutbox $outbox){

        $outbox->delete();
        return back()->with('ok','削除しました');
    }
}
