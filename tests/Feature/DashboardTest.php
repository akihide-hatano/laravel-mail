<?php

namespace Tests\Feature;

use App\Models\MailInbox;
use App\Models\MailOutbox;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;


class DashboardTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    public function test_dashborad_requires_auth(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_dashboard_shows_latest_5_for_outbox_and_inbox(): void
    {
        $user = User::factory()->create();


        $outCreadted = collect();
        for ($i = 1; $i <= 7; $i++ ){
            $outCreadted->push(
                MailOutbox::factory()->create([
                    'created_at'=>Carbon::now()->subMinutes(10-$i),
                ])
            );
        }
        // “最新5件”の想定（id なら末尾5件、created_at desc で上位5件）
        $expectedOutboxIds = $outCreadted->sortByDesc('received_at')->take(5)->pluck('id')->all();
        
        //Inboxを7件作成
        $inCreated = collect();
        for($i=1; $i<=7; $i++){
            $inCreated->push(
                MailInbox::factory()->create([
                    'received_at'=>Carbon::now()->subMinutes(10-$i),
                    ])
                );
            }
        $expectedInboxIds = $inCreated->sortByDesc('received_at')->take(5)->pluck('id')->all();

         $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            // ビュー変数 outboxes が “5件” かつ “期待IDと一致” しているか
            ->assertViewHas('outboxRecent', function ($col) use ($expectedOutboxIds) {
                return $col->count() === 5
                    && $col->pluck('id')->all() === $expectedOutboxIds;
            })
            // ビュー変数 inboxes も同様に
            ->assertViewHas('inboxRecent', function ($col) use ($expectedInboxIds) {
                return $col->count() === 5
                    && $col->pluck('id')->all() === $expectedInboxIds;
            });


    }
}
