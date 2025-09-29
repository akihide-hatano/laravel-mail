<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mail_outbox', function (Blueprint $t) {
            $t->id();
            // 宛先＆内容
            $t->string('to_email');
            $t->string('subject')->nullable();
            $t->longText('body')->nullable();
            // 送信状態
            $t->enum('status', ['draft','queued','sent','failed'])->default('draft')->index();
            $t->timestamp('queued_at')->nullable();
            $t->timestamp('sent_at')->nullable();
            $t->timestamp('failed_at')->nullable();
            $t->softDeletes();
            $t->string('fail_reason', 191)->nullable();
            // 追跡用（任意の外部ID等）
            $t->string('provider_message_id')->nullable();
            $t->json('provider_meta')->nullable();
            $t->timestamps();

            $t->index(['to_email','subject']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('mail_outbox');
    }
};