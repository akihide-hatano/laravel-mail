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
            'subject'  => $this->faker->boolean(80) ? $this->faker->sentence(4) : null,
            'body'     => $this->faker->paragraphs(2, true),
            'status'   => $status,
            'queued_at'=> $queued,
            'sent_at'  => $sent,
            'failed_at'=> $failed,
            'fail_reason' => $status==='failed' ? $this->faker->randomElement(['SMTP 550','Timeout','Auth error']) : null,
            'provider_message_id' => $status==='sent' ? $this->faker->uuid() : null,
            'provider_meta' => $status==='sent' ? ['relay'=>'mailpit','attempts'=>1] : null,
        ];
    }
}
