<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailOutbox extends Model
{
    /** @use HasFactory<\Database\Factories\MailOutboxFactory> */
    use HasFactory;
    use SoftDeletes; // ← 追加

    protected $table = 'mail_outbox';

    protected $fillable = [
        'to_email',
        'subject',
        'body','status',
        'queued_at',
        'sent_at',
        'failed_at',
        'fail_reason',
        'provider_message_id',
        'provider_meta'
    ];
    protected $casts = [
        'queued_at'=>'datetime',
        'sent_at'=>'datetime',
        'failed_at'=>'datetime',
        'provider_meta'=>'array',
    ];
}
