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
}
