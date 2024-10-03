<?php

namespace App\Enums;

enum OrderStatus: string
{
    case REVIEWED = "Reviewed";
    case ORDERED = "Ordered";
    case DELIVERED = "Delivered";
    case ONHOLD = "OnHold";
    case REFUNDED = "Refunded";
    case CANCELLED = "Cancelled";
    case ADJUSTMENT = "Adjusment";
    case SCAMMED = "SCAMMED";

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
