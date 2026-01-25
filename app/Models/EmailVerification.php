<?php

namespace App\Models;

use App\Enums\EmailVerificationEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'email',
        'verification_code',
        'expires_at',
        'type',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'type' => EmailVerificationEnum::class,
            'is_verified' => 'boolean',
        ];
    }

    public function scopeValid(Builder $query): void
    {
        $query->where('expires_at', '>', Carbon::now());
    }

    public function scopeExpired(Builder $query): void
    {
        $query->where('expires_at', '<=', Carbon::now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return ! $this->isExpired();
    }
}
