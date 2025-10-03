<?php

namespace App\Http\Controllers;

use App\Enums\OutboxStatus;
use App\Http\Requests\StoreOutboxRequest;
use App\Http\Requests\UpdateOutboxRequest;
use App\Jobs\SendOutboxMail;
use App\Models\MailOutbox;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OutboxController extends Controller
{
    public function index(){
        $items = MailOutbox::query()->latest()->paginate(10);
        $draft = OutboxStatus::Draft;
        // dd($items->getCollection()->map(fn($m) => $m->status->value));

        return view('outbox.index',compact('items','draft'));
    }


    public function create(){
        return view('outbox.create');
    }

    public function store(StoreOutboxRequest $request){

        $data = $request->validated();

        $row = MailOutbox::create([
            'to_email'  => $data['to_email'],
            'subject'   => $data['subject'] ?? null,
            'body'      => $data['body'] ?? null,
            'status'    => OutboxStatus::Queued,
            'queued_at' => now(),
        ]);

        // ★ キュー投入（必要なら →delay(...) で予約送信）
        SendOutboxMail::dispatch($row->id);

        // まずは保存のみ（送信は後で queue を差し込む）
        return to_route('mail.outbox.index')->with('ok', '送信キューに登録しました');
    }

    public function show(MailOutbox $outbox)
    {
        return view('outbox.show', compact('outbox'));
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

    public function destroy(MailOutbox $outbox){

        $outbox->delete();
        return to_route('mail.outbox.index')->with('ok', '削除しました');
    }

    //選択削除
    public function bulkDestroy(Request $request){

        //チェックされたid[]
    $ids = collect(Arr::wrap($request->input('ids',[])))
            ->filter(fn($v)=>is_numeric($v))
            ->map(fn($v) => (int)$v)
            ->unique()
            ->values();


        if(empty($ids)){
            return back()->with('warn','対象が選択されていません');
        }

        //softDeletesなので論理削除
        $count = MailOutbox::whereIn('id',$ids)->delete();
        return back()->with('ok','{$count}件削除しました');
    }
}
