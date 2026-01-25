<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Contracts\UserRepositoryContract;
use App\Models\User;

class UserRepository implements UserRepositoryContract
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function markEmailAsVerified(User $user): bool
    {
        return $user->update([
            'email_verified_at' => now(),
        ]);
    }
}
