<?php

namespace Tests\Feature;

use App\Enums\NotificationType;
use App\Jobs\MatchEoisWithInvitationRound;
use App\Models\Eoi;
use App\Models\InvitationRound;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VisaSubclass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationMatchingTest extends TestCase
{
    use RefreshDatabase;

    public function test_matching_user_receives_eligible_notification(): void
    {
        $subclass = VisaSubclass::factory()->create();
        $user = User::factory()->create();
        Eoi::factory()->create(['user_id' => $user->id, 'subclass_id' => $subclass->id, 'total_points' => 80]);

        $round = InvitationRound::factory()->create([
            'subclass_id' => $subclass->id,
            'minimum_points' => 75,
        ]);

        MatchEoisWithInvitationRound::dispatch($round);
        (new MatchEoisWithInvitationRound($round))->handle(app(\App\Services\NotificationService::class));

        $this->assertDatabaseHas('user_notifications', [
            'user_id' => $user->id,
            'type' => NotificationType::NewInvitationRound->value,
        ]);
    }

    public function test_close_but_under_threshold_user_receives_close_notification(): void
    {
        $subclass = VisaSubclass::factory()->create();
        $user = User::factory()->create();
        Eoi::factory()->create(['user_id' => $user->id, 'subclass_id' => $subclass->id, 'total_points' => 72]);

        $round = InvitationRound::factory()->create([
            'subclass_id' => $subclass->id,
            'minimum_points' => 75,
        ]);

        (new MatchEoisWithInvitationRound($round))->handle(app(\App\Services\NotificationService::class));

        $notification = UserNotification::where('user_id', $user->id)->first();
        $this->assertNotNull($notification);
        $this->assertStringContainsString('close', strtolower($notification->title));
    }

    public function test_user_well_below_threshold_receives_no_notification(): void
    {
        $subclass = VisaSubclass::factory()->create();
        $user = User::factory()->create();
        Eoi::factory()->create(['user_id' => $user->id, 'subclass_id' => $subclass->id, 'total_points' => 50]);

        $round = InvitationRound::factory()->create([
            'subclass_id' => $subclass->id,
            'minimum_points' => 75,
        ]);

        (new MatchEoisWithInvitationRound($round))->handle(app(\App\Services\NotificationService::class));

        $this->assertDatabaseMissing('user_notifications', ['user_id' => $user->id]);
    }

    public function test_different_subclass_eoi_does_not_match(): void
    {
        $subclassA = VisaSubclass::factory()->create();
        $subclassB = VisaSubclass::factory()->create();
        $user = User::factory()->create();
        Eoi::factory()->create(['user_id' => $user->id, 'subclass_id' => $subclassB->id, 'total_points' => 90]);

        $round = InvitationRound::factory()->create([
            'subclass_id' => $subclassA->id,
            'minimum_points' => 65,
        ]);

        (new MatchEoisWithInvitationRound($round))->handle(app(\App\Services\NotificationService::class));

        $this->assertDatabaseMissing('user_notifications', ['user_id' => $user->id]);
    }
}
