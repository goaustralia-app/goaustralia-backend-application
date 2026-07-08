<?php

namespace App\Jobs;

use App\Enums\NotificationType;
use App\Models\Eoi;
use App\Models\InvitationRound;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class MatchEoisWithInvitationRound implements ShouldQueue
{
    use Queueable;

    public function __construct(public InvitationRound $round) {}

    public function handle(NotificationService $notificationService): void
    {
        $threshold = config('invitation.threshold_points', 5);
        $minPoints = $this->round->minimum_points;

        if ($minPoints === null) {
            return;
        }

        $query = Eoi::with('user')
            ->where('subclass_id', $this->round->subclass_id)
            ->whereNull('deleted_at');

        $eois = $query->get();

        foreach ($eois as $eoi) {
            $userPoints = $eoi->total_points;

            if ($userPoints >= $minPoints) {
                $notificationService->create(
                    $eoi->user,
                    NotificationType::NewInvitationRound,
                    'New Invitation Round — You May Be Eligible',
                    "A new invitation round was issued for Subclass {$this->round->subclass?->subclass_code}. The minimum points were {$minPoints} and your EOI has {$userPoints} points.",
                    ['invitation_round_id' => $this->round->id, 'eoi_id' => $eoi->id]
                );
            } elseif ($userPoints >= ($minPoints - $threshold)) {
                $pointsShort = $minPoints - $userPoints;

                $notificationService->create(
                    $eoi->user,
                    NotificationType::NewInvitationRound,
                    'New Invitation Round — You Were Close',
                    "A new invitation round required {$minPoints} points. Your EOI scored {$userPoints} — just {$pointsShort} point(s) short.",
                    ['invitation_round_id' => $this->round->id, 'eoi_id' => $eoi->id]
                );
            }
        }
    }
}
