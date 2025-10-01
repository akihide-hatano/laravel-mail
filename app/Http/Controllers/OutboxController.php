<?php

namespace App\Http\Controllers;

use App\Enums\OutboxStatus;
use App\Http\Requests\StoreOutboxRequest;
use App\Http\Requests\UpdateOutboxRequest;
use App\Models\MailOutbox;
use Illuminate\Http\Request;

class OutboxController extends Controller
{
    public function index(){
        $items = MailOutbox::query()->latest()->paginate(10);
        return view('outbox.index',compact('items'));
    }


    public function create(){
        return view('outbox.create');
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
        return to_route('mail.outbox.index')->with('ok', '送信キューに登録しました');
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
        // 画面遷移ガード（UIに出さなくても最終防衛）
        abort_if($outbox->status !== OutboxStatus::Draft,403);
        return view('outbox.edit',compact('outbox'));
    }

    public function update(UpdateOutboxRequest $request,MailOutbox $outbox){

        $outbox->update($request->validated());
        return to_route('mail.outbox.show',$outbox)->with('ok','更新しました');
    }
}
