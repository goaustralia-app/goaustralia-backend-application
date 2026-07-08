<?php

namespace App\Enums;

enum DocumentType: string
{
    case Passport = 'passport';
    case EnglishTest = 'english_test';
    case SkillsAssessment = 'skills_assessment';
    case EmploymentLetter = 'employment_letter';
    case EducationalCertificate = 'educational_certificate';
    case StateNomination = 'state_nomination';
    case Other = 'other';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
