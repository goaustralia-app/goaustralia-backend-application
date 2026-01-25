<?php

namespace App\Domains\EmailVerification\Repositories;

use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Enums\EmailVerificationEnum;
use App\Models\EmailVerification;

class EmailVerificationRepository implements EmailVerificationRepositoryContract
{
    public function create(array $data): EmailVerification
    {
        return EmailVerification::create($data);
    }

    public function findValidByEmail(string $email, EmailVerificationEnum $type): ?EmailVerification
    {
        return EmailVerification::where('email', $email)
            ->where('type', $type)
            ->valid()
            ->latest()
            ->first();
    }

    public function deleteByEmail(string $email, EmailVerificationEnum $type): bool
    {
        return EmailVerification::where('email', $email)
            ->where('type', $type)
            ->delete();
    }

    public function deleteExpired(): int
    {
        return EmailVerification::expired()->delete();
    }
}
