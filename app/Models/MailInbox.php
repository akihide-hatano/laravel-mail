<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailInbox extends Model
{
    /** @use HasFactory<\Database\Factories\MailInboxFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'mail_inbox';
    protected $fillable = [
        'from_email',
        'subject',
        'body',
        'received_at',
        'is_read'];
    protected $casts = [
        'received_at'=>'datetime',
        'is_read'=>'boolean'];

}
