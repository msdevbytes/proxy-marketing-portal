<?php

namespace App\Enums;

enum ProductStatus: string
{
    case ENABLED = "Enabled";
    case DISABLED = "Disabled";

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
