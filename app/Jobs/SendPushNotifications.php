<?php

namespace App\Jobs;

use App\Enums\NotificationType;
use App\Enums\ReminderStatus;
use App\Models\Reminder;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPushNotifications implements ShouldQueue
{
    use Queueable;

    public function handle(NotificationService $notificationService): void
    {
        Reminder::with('user')
            ->pending()
            ->dueToday()
            ->each(function (Reminder $reminder) use ($notificationService) {
                $notificationService->create(
                    $reminder->user,
                    NotificationType::Reminder,
                    'Upcoming Expiry Reminder',
                    "You have a {$reminder->reminder_type->value} reminder due.",
                    ['reminder_id' => $reminder->id, 'reference_id' => $reminder->reference_id]
                );

                $reminder->update([
                    'status' => ReminderStatus::Sent,
                    'sent_at' => now(),
                ]);
            });
    }
}
