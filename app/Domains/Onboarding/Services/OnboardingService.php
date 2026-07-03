<?php

namespace App\Domains\Onboarding\Services;

use App\Domains\Onboarding\Repositories\OnboardingRepositoryInterface;
use App\Http\Resources\OnboardingQuestionResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OnboardingService
{
    public function __construct(
        private OnboardingRepositoryInterface $repository
    ) {}

    public function getQuestions(): AnonymousResourceCollection
    {
        return OnboardingQuestionResource::collection($this->repository->getAllQuestions());
    }

    public function submitAnswers(User $user, array $answers): void
    {
        if ($user->is_onboard) {
            throw new \RuntimeException('Onboarding has already been completed. Use the update endpoint to make changes.');
        }

        $this->repository->submitAnswers($user, $answers);
    }

    public function getUserAnswers(User $user): array
    {
        return $this->repository->getUserAnswers($user);
    }

    public function updateAnswers(User $user, array $answers): void
    {
        if (! $user->is_onboard) {
            throw new \RuntimeException('Onboarding has not been submitted yet. Use the submit endpoint first.');
        }

        $this->repository->updateAnswers($user, $answers);
    }
}
