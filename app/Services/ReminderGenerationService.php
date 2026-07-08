<?php

namespace App\Services;

use App\Enums\ReminderType;
use App\Models\Document;
use App\Models\Eoi;
use App\Models\Reminder;

class ReminderGenerationService
{
    public function generateForEoi(Eoi $eoi): ?Reminder
    {
        if (! $eoi->expiry_date) {
            return null;
        }

        return Reminder::updateOrCreate(
            [
                'user_id' => $eoi->user_id,
                'reminder_type' => ReminderType::EoiExpiry,
                'reference_id' => $eoi->id,
            ],
            [
                'reminder_date' => $eoi->expiry_date->subDays(30),
                'status' => 'pending',
            ]
        );
    }

    public function generateForDocument(Document $document): ?Reminder
    {
        if (! $document->expiry_date) {
            return null;
        }

        $type = $this->resolveDocumentReminderType($document);

        return Reminder::updateOrCreate(
            [
                'user_id' => $document->user_id,
                'reminder_type' => $type,
                'reference_id' => $document->id,
            ],
            [
                'reminder_date' => $document->expiry_date->subDays($document->reminder_days),
                'status' => 'pending',
            ]
        );
    }

    private function resolveDocumentReminderType(Document $document): ReminderType
    {
        return match ($document->document_type) {
            \App\Enums\DocumentType::Passport => ReminderType::PassportExpiry,
            \App\Enums\DocumentType::EnglishTest => ReminderType::EnglishTestExpiry,
            \App\Enums\DocumentType::SkillsAssessment => ReminderType::SkillsAssessmentExpiry,
            \App\Enums\DocumentType::StateNomination => ReminderType::StateNominationExpiry,
            default => ReminderType::DocumentExpiry,
        };
    }
}
