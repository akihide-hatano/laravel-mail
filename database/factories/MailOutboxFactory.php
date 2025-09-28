<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailOutbox>
 */
class MailOutboxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $status = $this->faker->randomElement(['draft','queued','sent','failed']);
        $base   = $this->faker->dateTimeBetween('-14 days','now');

        $queued = in_array($status,['queued','sent','failed']) ? $base : null;
        $sent   = $status === 'sent'   ? $this->faker->dateTimeBetween($base,'now') : null;
        $failed = $status === 'failed' ? $this->faker->dateTimeBetween($base,'now') : null;

        return [
            'to_email' => $this->faker->unique()->safeEmail(),
            'subject'  => $this->faker->boolean(80) ? $this->jaSubject() : null, // ← 変更
            'body'     => $this->jaBody(),                                       // ← 変更
            'status'   => $status,
            'queued_at'=> $queued,
            'sent_at'  => $sent,
            'failed_at'=> $failed,
            'fail_reason' => $status==='failed'
                ? $this->faker->randomElement(['SMTP 550','Timeout','Auth error'])
                : null,
            'provider_message_id' => $status==='sent' ? $this->faker->uuid() : null,
            'provider_meta' => $status==='sent' ? ['relay'=>'mailpit','attempts'=>1] : null,
        ];

    }

    public function draft(): static {
        return $this->state(fn () => [
            'status' => 'draft',
            'queued_at' => null,
            'sent_at' => null,
            'failed_at' => null,
            'fail_reason' => null,
            'provider_message_id' => null,
            'provider_meta' => null,
        ]);
    }

    public function queued(): static {
        return $this->state(fn () => [
            'status' => 'queued',
            'queued_at' => now(),
            'sent_at' => null,
            'failed_at' => null,
            'fail_reason' => null,
            'provider_message_id' => null,
            'provider_meta' => null,
        ]);
    }

    public function sent(): static {
        return $this->state(fn () => [
            'status' => 'sent',
            'queued_at' => now()->subMinutes(5),
            'sent_at' => now(),
            'failed_at' => null,
            'fail_reason' => null,
            'provider_message_id' => $this->faker->uuid(),
            'provider_meta' => ['relay'=>'mailpit','attempts'=>1],
        ]);
    }

    public function failed(): static {
        return $this->state(fn () => [
            'status' => 'failed',
            'queued_at' => now()->subMinutes(5),
            'sent_at' => null,
            'failed_at' => now(),
            'fail_reason' => 'SMTP 550',
            'provider_message_id' => null,
            'provider_meta' => null,
        ]);
    }

        protected function jaSubject(): string
    {
        $f = $this->faker;
        return $f->randomElement([
            '【重要】パスワード再設定のご案内',
            "【至急】ご確認ください：請求書 No.{$f->numberBetween(1000,9999)}",
            '見積書送付の件',
            '打ち合わせ日程のご相談',
            'ミーティングのリマインド（'.$f->date('n/j').'）',
            'ご注文ありがとうございます（注文番号：'.$f->bothify('????-#####').'）',
            '新機能リリースのお知らせ',
            '採用面談のご連絡（'.$f->date('n/j H:i').'）',
        ]);
    }

    protected function jaBody(): string
    {
        $f = $this->faker;
        $lines = [
            "お世話になっております。{$f->company()}の{$f->lastName()}です。",
            '下記の件につきましてご連絡いたしました。',
            '・内容：ご確認事項の共有',
            '・期日：'.$f->date('Y/m/d')."（".$f->randomElement(['月','火','水','木','金'])."）",
            '',
            'ご不明点がございましたら、本メールへご返信ください。',
            '引き続きよろしくお願いいたします。',
            '',
            $f->company(),
            $f->lastName().' '.$f->firstName(),
            'TEL: 0'.$f->numerify('##-####-####'),
        ];
        return implode("\n", $lines);
    }

}
