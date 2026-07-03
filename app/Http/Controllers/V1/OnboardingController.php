<?php

namespace App\Http\Controllers\V1;

use App\Domains\Onboarding\Services\OnboardingService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SubmitOnboardingAnswersRequest;
use Illuminate\Http\JsonResponse;

class OnboardingController extends BaseController
{
    public function __construct(
        private OnboardingService $onboardingService
    ) {}

    public function questions(): JsonResponse
    {
        try {
            $questions = $this->onboardingService->getQuestions();

            return $this->successResponse('Onboarding questions retrieved successfully.', $questions);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function submitAnswers(SubmitOnboardingAnswersRequest $request): JsonResponse
    {
        try {
            $this->onboardingService->submitAnswers($request->user(), $request->validated('answers'));

            return $this->successResponse('Onboarding answers submitted successfully.');
        } catch (\RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function getAnswers(): JsonResponse
    {
        try {
            $answers = $this->onboardingService->getUserAnswers(request()->user());

            return $this->successResponse('Onboarding answers retrieved successfully.', ['answers' => $answers]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    public function updateAnswers(SubmitOnboardingAnswersRequest $request): JsonResponse
    {
        try {
            $this->onboardingService->updateAnswers($request->user(), $request->validated('answers'));

            return $this->successResponse('Onboarding answers updated successfully.');
        } catch (\RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
