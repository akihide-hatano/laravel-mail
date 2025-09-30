<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutboxRequest;
use App\Models\MailOutbox;
use Illuminate\Http\Request;

class OutboxController extends Controller
{
    public function index(){
        $items = MailOutbox::query()->latest()->paginate(10);
        return view('outbox.index',compact('items'));
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
        return redirect()->route('outbox.index')->with('ok', '送信キューに登録しました');
    }

    public function show(MailOutbox $outbox)
    {
        return view('outbox.show', compact('outbox'));
    }
    public function destroy(MailOutbox $outbox){

        $outbox->delete();
        return to_route('mail.outbox.index')->with('ok', '削除しました');
    }

    public function edit(MailOutbox $outbox){
        return view('outbox.edit',compact('outbox'));
    }

    public function update(Request $request,MailOutbox $outbox){
        
    }
}
