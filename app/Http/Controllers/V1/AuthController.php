<?php

namespace App\Http\Controllers\V1;

use App\Domains\EmailVerification\Services\EmailVerificationService;
use App\Domains\User\Services\LoginService;
use App\Domains\User\Services\RegistrationService;
use App\Domains\User\Services\SocialLoginService;
use App\Http\Controllers\BaseController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\SocialLoginRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    public function __construct(
        private RegistrationService $registrationService,
        private EmailVerificationService $emailVerificationService,
        private LoginService $loginService,
        private SocialLoginService $socialLoginService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->registrationService->register($request->validated());

            $data = [
                'user' => $result['user'],
                'email' => $result['email'],
                'access_token' => $result['access_token'],
            ];

            return $this->successResponse($result['message'], $data, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        try {
            $result = $this->emailVerificationService->verifyEmail(
                $request->email,
                $request->verification_code
            );

            $data = [
                'email' => $result['email'],
            ];

            return $this->successResponse($result['message'], $data);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        try {
            $result = $this->emailVerificationService->resendOtp($request->email);

            return $this->successResponse($result['message'], ['email' => $result['email']]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        try {
            $result = $this->emailVerificationService->sendOtp($request->email);

            return $this->successResponse($result['message'], ['email' => $result['email'], 'otp' => $result['otp']]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->loginService->login($request->validated());

            return $this->successResponse($result['message'], new AuthResource($result));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }

    public function socialLogin(SocialLoginRequest $request): JsonResponse
    {
        try {
            $result = $this->socialLoginService->login($request->validated());

            return $this->successResponse($result['message'], new AuthResource($result));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
