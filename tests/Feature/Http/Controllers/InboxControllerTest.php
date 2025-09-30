<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MailInbox;
use App\Models\User;

class InboxControllerTest extends TestCase
{
    /**
     * A basic feature test example.
    */
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_requires_auth():void{
        $this->get('/mail/inbox')->assertRedirect('/login');
    }

    public function test_store_creates_record_and_redirects(): void{
        $user = User::factory()->create();

        $payload = [
            'from_email' => 'foo@example.com',
            'subject'    => 'テスト件名',
            'body'       => '本文',
        ];

        $this->actingAs($user)
                ->post(route('mail.inbox.store'),$payload)
                ->assertRedirect(route('mail.inbox.index'));

        $this->assertDatabaseHas('mail_inbox',[
            'from_email' => 'foo@example.com',
            'subject'    => 'テスト件名',
            'is_read'    => 0,
        ]);
    }

    public function test_update_toggles_read_flag():void{

        $user = User::factory()->create();
        $row = MailInbox::factory()->create(['is_read' => false]);

        $this->actingAs($user)
            ->patch(route('mail.inbox.update', $row))
            ->assertRedirect(route('mail.inbox.index'));

        $this->assertTrue($row->fresh()->is_read);
    }
}
