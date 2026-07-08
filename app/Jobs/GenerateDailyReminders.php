<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\Eoi;
use App\Services\ReminderGenerationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateDailyReminders implements ShouldQueue
{
    use Queueable;

    public function handle(ReminderGenerationService $service): void
    {
        Eoi::whereNotNull('expiry_date')
            ->whereNull('deleted_at')
            ->each(fn (Eoi $eoi) => $service->generateForEoi($eoi));

        Document::whereNotNull('expiry_date')
            ->whereNull('deleted_at')
            ->each(fn (Document $doc) => $service->generateForDocument($doc));
    }
}
