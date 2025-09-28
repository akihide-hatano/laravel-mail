<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailOutbox extends Model
{
    /** @use HasFactory<\Database\Factories\MailOutboxFactory> */
    use HasFactory;

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
