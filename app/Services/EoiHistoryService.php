<?php

namespace App\Services;

use App\Models\Eoi;
use App\Models\EoiHistory;

class EoiHistoryService
{
    /**
     * @param  array<string, mixed>  $previousData  Snapshot of the EOI before update
     */
    public function record(Eoi $eoi, array $previousData): EoiHistory
    {
        $updatedData = $eoi->only(array_keys($previousData));
        $changed = array_diff_assoc($updatedData, $previousData);

        return EoiHistory::create([
            'eoi_id' => $eoi->id,
            'change_type' => 'updated',
            'previous_value' => array_intersect_key($previousData, $changed),
            'updated_value' => $changed,
            'changed_at' => now(),
        ]);
    }
}
