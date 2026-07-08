<?php

namespace Tests\Feature;

use App\Enums\ReminderStatus;
use App\Enums\ReminderType;
use App\Models\Reminder;
use App\Models\User;
use App\Services\ReminderGenerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_cannot_access_reminders(): void
    {
        $this->getJson('/api/v1/reminders')->assertStatus(401);
    }

    public function test_user_can_list_own_reminders(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Reminder::factory()->create(['user_id' => $user->id]);
        Reminder::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($user, 'api')->getJson('/api/v1/reminders');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data.data'));
    }

    public function test_user_can_dismiss_reminder(): void
    {
        $user = User::factory()->create();
        $reminder = Reminder::factory()->create([
            'user_id' => $user->id,
            'status' => ReminderStatus::Pending,
        ]);

        $this->actingAs($user, 'api')
            ->patchJson("/api/v1/reminders/{$reminder->id}/read")
            ->assertStatus(200)
            ->assertJsonPath('data.status', ReminderStatus::Dismissed->value);
    }

    public function test_user_cannot_dismiss_another_users_reminder(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $reminder = Reminder::factory()->create(['user_id' => $other->id]);

        $this->actingAs($user, 'api')
            ->patchJson("/api/v1/reminders/{$reminder->id}/read")
            ->assertStatus(404);
    }

    public function test_reminder_generation_service_creates_eoi_reminder(): void
    {
        $user = User::factory()->create();
        $eoi = \App\Models\Eoi::factory()->create([
            'user_id' => $user->id,
            'expiry_date' => now()->addDays(60)->toDateString(),
        ]);

        $service = new ReminderGenerationService;
        $reminder = $service->generateForEoi($eoi);

        $this->assertNotNull($reminder);
        $this->assertEquals(ReminderType::EoiExpiry, $reminder->reminder_type);
        $this->assertEquals($eoi->id, $reminder->reference_id);
    }

    public function test_reminder_generation_service_skips_eoi_without_expiry(): void
    {
        $user = User::factory()->create();
        $eoi = \App\Models\Eoi::factory()->create([
            'user_id' => $user->id,
            'expiry_date' => null,
        ]);

        $service = new ReminderGenerationService;
        $result = $service->generateForEoi($eoi);

        $this->assertNull($result);
    }
}
