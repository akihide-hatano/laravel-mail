<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;    // ★ これが必要
use Illuminate\Foundation\Bus\Dispatchable;    // ★ これが必要
use Illuminate\Queue\InteractsWithQueue;       // ★ これが必要
use Illuminate\Queue\SerializesModels;         // ★ これが必要
use Illuminate\Foundation\Queue\Queueable;

class SendOutboxMail implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
