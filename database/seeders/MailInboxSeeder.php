<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MailInbox;

class MailInboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        fake()->unique(true);

        MailInbox::factory()->count(15)->unread()->create();
        MailInbox::factory()->count(10)->read()->create();
    }
}
