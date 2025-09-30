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

        public function colorClasses():string{
            return match($this){
                self::Draft  => 'bg-blue-100 text-blue-800 ring-blue-200',
                self::Queued => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
                self::Sent   => 'bg-yellow-100 text-yellow-800 ring-yellow-200',
                self::Failed => 'bg-rose-100 text-rose-800 ring-rose-200',
            };
        }
    }
?>