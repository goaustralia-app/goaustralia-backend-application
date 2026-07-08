<?php

namespace App\Jobs;

use App\Models\Reminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendEmailNotifications implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Placeholder: integrate with a mail provider (e.g. Laravel Mail + Mailgun/SES).
        // Query pending reminders due today and send emails via a dedicated Mailable.
        Reminder::with('user')
            ->pending()
            ->dueToday()
            ->each(function (Reminder $reminder) {
                Log::info('Email reminder queued', [
                    'user_id' => $reminder->user_id,
                    'type' => $reminder->reminder_type->value,
                    'reference_id' => $reminder->reference_id,
                ]);
            });
    }
}
