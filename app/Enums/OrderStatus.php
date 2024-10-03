<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ORDERED = "Ordered";
    case REVIEWED = "Reviewed";
    case DELIVERED = "Delivered";
    case REFUNDED = "Refunded";
    case COMPLETED = "Completed";
    case ONHOLD = "OnHold";
    case CANCELLED = "Cancelled";
    case ADJUSTMENT = "Adjustment";
    case SCAMMED = "Scammed";

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
