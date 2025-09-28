<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mail_inbox', function (Blueprint $t) {
            $t->id();
            // 送信元＆内容
            $t->string('from_email');
            $t->string('subject')->nullable();
            $t->longText('body')->nullable();
            // 受信時刻
            $t->timestamp('received_at')->nullable()->index();
            // 将来のラベル/既読フラグ（拡張しやすいよう最低限）
            $t->boolean('is_read')->default(false)->index();
            $t->timestamps();

            $t->index(['from_email','subject']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('mail_inbox');
    }
};

