<?php

namespace App\Enums;

enum ReminderType: string
{
    case EoiExpiry = 'eoi_expiry';
    case PassportExpiry = 'passport_expiry';
    case EnglishTestExpiry = 'english_test_expiry';
    case SkillsAssessmentExpiry = 'skills_assessment_expiry';
    case StateNominationExpiry = 'state_nomination_expiry';
    case DocumentExpiry = 'document_expiry';
    case Custom = 'custom';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
