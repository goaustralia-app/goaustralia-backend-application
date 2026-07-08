<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'eoi_id',
        'document_type',
        'document_name',
        'expiry_date',
        'issue_date',
        'attachment_url',
        'reminder_days',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => DocumentType::class,
            'expiry_date' => 'date',
            'issue_date' => 'date',
            'reminder_days' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function eoi(): BelongsTo
    {
        return $this->belongsTo(Eoi::class);
    }

    public function scopeExpiringSoon($query)
    {
        $driver = \Illuminate\Support\Facades\DB::getDriverName();

        $condition = $driver === 'sqlite'
            ? "expiry_date <= date('now', '+' || CAST(reminder_days AS TEXT) || ' days')"
            : 'expiry_date <= DATE_ADD(CURDATE(), INTERVAL reminder_days DAY)';

        return $query->whereNotNull('expiry_date')
            ->whereRaw($condition)
            ->where('expiry_date', '>=', now()->toDateString());
    }
}
