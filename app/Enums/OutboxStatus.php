<?php

    namespace App\Enums;

use function Laravel\Prompts\outro;

    enum OutboxStatus: string
    {
        case Draft = 'draft';
        case Queued = 'queued';
        case Sent   = 'sent';
        case Failed = 'failed';
        
        public function label() : string {
            return match($this){
            self::Draft  => '下書き',
            self::Queued => '送信待ち',
            self::Sent   => '送信済み',
            self::Failed => '失敗',
            };
        }
    }
?>