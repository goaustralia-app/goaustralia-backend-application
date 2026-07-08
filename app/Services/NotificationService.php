<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\User;
use App\Models\UserNotification;

class NotificationService
{
    public function create(
        User $user,
        NotificationType $type,
        string $title,
        string $message,
        array $metadata = []
    ): UserNotification {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'metadata' => empty($metadata) ? null : $metadata,
        ]);
    }
}
