<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\MailOutbox;
use App\Models\User;
use App\Enums\OutboxStatus;
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
            ->post(route('mail.outbox.store'), $payload)
            ->assertRedirect(route('mail.outbox.index'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('ok','送信キューに登録しました');

        $this->assertDatabaseHas('mail_outbox', ['to_email'=>'bar@example.com','status'=>'queued']);

        // queued_at が入っていることも確認
        $row = MailOutbox::firstWhere('to_email', 'bar@example.com');
        $this->assertNotNull($row->queued_at);
    }

    public function test_edit_update_destroy(): void
    {
        $user = User::factory()->create();
        $row  = MailOutbox::factory()->create(['status' => 'draft']);

        // edit
        $this->actingAs($user)->get(route('mail.outbox.edit', $row))->assertOk();

        // update
        $this->actingAs($user)
            ->patch(route('mail.outbox.update', $row), [
                'to_email' => 'baz@example.com',
                'subject'  => '更新件名',
                'body'     => '更新本文',
                // 'status'   => 'queued',
            ])->assertRedirect(route('mail.outbox.show', $row));

        $this->assertDatabaseHas('mail_outbox', [
            'id'       => $row->id,
            'to_email' => 'baz@example.com',
            'status'   => 'draft',
        ]);

        // destroy
        $this->actingAs($user)
            ->delete(route('mail.outbox.destroy', $row))
            ->assertRedirect(route('mail.outbox.index'));

        $this->assertSoftDeleted('mail_outbox', ['id' => $row->id]);
    }

    public function test_edit_forbidden_when_not_draft():void{

        $user = User::factory()->create();
        $row = MailOutbox::factory()->create(['status'=>OutboxStatus::Queued]);

        $this->actingAs($user)
            ->get(route('mail.outbox.edit',$row))
            ->assertForbidden();

        $this->actingAs($user)
            ->patch(route('mail.outbox.update',$row))
            ->assertForbidden();

        
    }
}
