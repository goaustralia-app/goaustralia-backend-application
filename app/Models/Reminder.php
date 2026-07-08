<?php

namespace App\Models;

use App\Enums\ReminderStatus;
use App\Enums\ReminderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reminder_type',
        'reference_id',
        'reminder_date',
        'status',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'reminder_type' => ReminderType::class,
            'status' => ReminderStatus::class,
            'reminder_date' => 'date',
            'sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', ReminderStatus::Pending);
    }

    public function scopeDueToday($query)
    {
        return $query->where('reminder_date', '<=', now()->toDateString());
    }
}
