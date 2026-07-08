<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EoiHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'eoi_id',
        'change_type',
        'previous_value',
        'updated_value',
        'notes',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'previous_value' => 'array',
            'updated_value' => 'array',
            'changed_at' => 'datetime',
        ];
    }

    public function eoi(): BelongsTo
    {
        return $this->belongsTo(Eoi::class);
    }
}
