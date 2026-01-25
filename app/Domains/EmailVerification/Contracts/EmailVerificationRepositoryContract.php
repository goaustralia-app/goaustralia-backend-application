<?php

namespace App\Domains\EmailVerification\Contracts;

use App\Enums\EmailVerificationEnum;
use App\Models\EmailVerification;

interface EmailVerificationRepositoryContract
{
    public function create(array $data): EmailVerification;

    public function findValidByEmail(string $email, EmailVerificationEnum $type): ?EmailVerification;

    public function deleteByEmail(string $email, EmailVerificationEnum $type): bool;

    public function deleteExpired(): int;
}
