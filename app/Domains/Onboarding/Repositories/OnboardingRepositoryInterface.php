<?php

namespace App\Domains\Onboarding\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface OnboardingRepositoryInterface
{
    public function getAllQuestions(): Collection;

    public function submitAnswers(User $user, array $answers): void;

    public function getUserAnswers(User $user): array;

    public function updateAnswers(User $user, array $answers): void;
}
