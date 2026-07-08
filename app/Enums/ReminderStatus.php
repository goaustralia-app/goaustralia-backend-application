<?php

namespace App\Enums;

enum ReminderStatus: string
{
    case Pending = 'pending';
    case Sent = 'sent';
    case Dismissed = 'dismissed';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
