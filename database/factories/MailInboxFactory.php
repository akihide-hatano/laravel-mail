<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MailInbox>
 */
class MailInboxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_email'=>$this->faker->safeEmail(),
            'subject'     => $this->faker->boolean(85) ? $this->faker->sentence(5) : null,
            'body'        => $this->faker->paragraphs(2, true),
            'received_at' => $this->faker->dateTimeBetween('-14 days','now'),
            'is_read'     => $this->faker->boolean(40),
        ];
    }
}
