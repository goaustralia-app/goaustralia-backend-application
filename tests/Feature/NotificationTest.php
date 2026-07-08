<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_cannot_access_notifications(): void
    {
        $this->getJson('/api/v1/notifications')->assertStatus(401);
    }

    public function test_user_can_list_own_notifications(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        UserNotification::factory()->create(['user_id' => $user->id]);
        UserNotification::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/notifications');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $notification = UserNotification::factory()->create(['user_id' => $user->id, 'is_read' => false]);

        $this->actingAs($user, 'api')
            ->patchJson("/api/v1/notifications/{$notification->id}/read")
            ->assertStatus(200)
            ->assertJsonPath('data.is_read', true);

        $this->assertDatabaseHas('user_notifications', ['id' => $notification->id, 'is_read' => true]);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        UserNotification::factory()->count(3)->create(['user_id' => $user->id, 'is_read' => false]);

        $this->actingAs($user, 'api')
            ->patchJson('/api/v1/notifications/read-all')
            ->assertStatus(200);

        $unread = UserNotification::where('user_id', $user->id)->where('is_read', false)->count();
        $this->assertEquals(0, $unread);
    }

    public function test_user_cannot_read_another_users_notification(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $notification = UserNotification::factory()->create(['user_id' => $other->id]);

        $this->actingAs($user, 'api')
            ->patchJson("/api/v1/notifications/{$notification->id}/read")
            ->assertStatus(404);
    }

    public function test_notifications_are_paginated(): void
    {
        $user = User::factory()->create();
        UserNotification::factory()->count(20)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['data', 'meta' => ['total', 'per_page', 'current_page']]]);

        $this->assertEquals(15, count($response->json('data.data')));
    }
}
