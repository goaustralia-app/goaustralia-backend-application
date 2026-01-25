<?php

namespace App\Enums;

enum EmailVerificationEnum: string
{
    case REGISTRATION = 'registration';
    case FORGOT_PASSWORD = 'forgot-password';
    case TWO_FACTOR_AUTH = 'two-factor-auth';
    case RESEND_OTP = 'resend_otp';

    public const EMAIL_VERIFICATION = [
        self::REGISTRATION->value => self::REGISTRATION->value,
        self::FORGOT_PASSWORD->value => self::FORGOT_PASSWORD->value,
        self::TWO_FACTOR_AUTH->value => self::TWO_FACTOR_AUTH->value,
        self::RESEND_OTP->value => self::RESEND_OTP->value,
    ];

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
