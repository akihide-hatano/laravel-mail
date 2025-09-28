<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailInbox>
 */
class MailInboxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'from_email'  => $this->faker->safeEmail(),         // 送信元
            'subject'     => $this->faker->boolean(85) ? $this->jaSubject() : null,
            'body'        => $this->jaBody(),
            'received_at' => $this->faker->dateTimeBetween('-14 days','now'),
            'is_read'     => $this->faker->boolean(40),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn () => ['is_read' => false]);
    }

    public function read(): static
    {
        return $this->state(fn () => ['is_read' => true]);
    }

    // ---- ここから日本語用のヘルパ ----
    protected function jaSubject(): string
    {
        $f = $this->faker;
        return $f->randomElement([
            '【ご確認ください】アカウントのセキュリティ通知',
            '請求書送付の件（No.'.$f->numberBetween(1000,9999).'）',
            '面談日程のご提案（'.$f->date('n/j H:i').'）',
            '資料共有のご連絡',
            'パスワード再設定のお願い',
            'イベント参加のご案内',
            'お問い合わせへの回答',
            '納期に関するご相談',
        ]);
    }

    protected function jaBody(): string
    {
        $f = $this->faker;
        $lines = [
            "お世話になっております。{$f->company()}の{$f->lastName()}です。",
            'ご依頼いただいていた件につきまして、以下の通りご連絡いたします。',
            '',
            '・概要：確認事項の共有',
            '・期日：'.$f->date('Y/m/d')."（".$f->randomElement(['月','火','水','木','金'])."）",
            '',
            '添付資料もあわせてご確認ください。ご不明点があれば本メールへご返信ください。',
            '',
            '――――――――――――――――――――',
            $f->company(),
            $f->lastName().' '.$f->firstName(),
            'TEL: 0'.$f->numerify('##-####-####'),
        ];
        return implode("\n", $lines);
    }
}
