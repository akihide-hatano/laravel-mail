<?php

namespace App\Jobs;

use App\Enums\OutboxStatus;
use App\Models\MailOutbox;
use Illuminate\Bus\Queueable;                  // ← ここが正
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOutboxMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(public int $outboxId) {}

    public function handle(): void
    {
        $row = MailOutbox::findOrFail($this->outboxId);

        // 二重送信ガード（queued のときだけ送る）
        if($row->status !== OutboxStatus::Queued){
            return;
        }

        try {
            $row->update([
                'status'      => 'sent',
                'sent_at'     => now(),
                'failed_at'   => null,
                'fail_reason' => null,
            ]);
        } catch (Throwable $e) {
            $row->update([
                'status'      => 'failed',
                'failed_at'   => now(),
                'fail_reason' => substr($e->getMessage(), 0, 180),
            ]);
            throw $e;
        }
    }
}
