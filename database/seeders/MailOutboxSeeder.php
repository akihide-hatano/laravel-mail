<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MailOutbox;

class MailOutboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        fake()->unique(true);

        MailOutbox::factory()->count(5)->draft()->create();
        MailOutbox::factory()->count(10)->queued()->create();
        MailOutbox::factory()->count(8)->sent()->create();
        MailOutbox::factory()->count(4)->failed()->create();
    }
}
