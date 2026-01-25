<?php

namespace App\Enums;

enum ProviderNameEnum: string
{
    case GOOGLE = 'google';
    case APPLE = 'apple';

    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
