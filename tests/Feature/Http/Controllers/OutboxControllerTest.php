<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MailOutbox;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

class OutboxControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */

    use RefreshDatabase;

    public function test_index_ok():void{

        $user = User::factory()->create();
        $this->actingAs($user)->get('/mail/outbox')->assertOk();
    }

    public function test_store_queues_and_redirect():void{
        $user = User::factory()->create();
        $payload= ['to_email'=>'bar@example.com','subject'=>'送信テスト','body'=>'本文'];

        $this->actingAs($user)
            ->from(route('mail.outbox.index'))
            ->post(route('mail.outbox.store'), $payload)   // ← store にPOST & payloadを渡す
            ->assertRedirect(route('mail.outbox.index'))   // ← 期待通り index へ
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('mail_outbox', ['to_email'=>'bar@example.com','status'=>'queued']);
    }
}
