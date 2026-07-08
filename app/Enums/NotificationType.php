<?php

namespace App\Enums;

enum NotificationType: string
{
    case NewInvitationRound = 'new_invitation_round';
    case OccupationUpdate = 'occupation_update';
    case DocumentExpiry = 'document_expiry';
    case EoiExpiry = 'eoi_expiry';
    case Reminder = 'reminder';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
