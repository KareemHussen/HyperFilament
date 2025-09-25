<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TripStatus: int implements HasColor, HasLabel
{
    case PENDING = 0;
    case APPROVED = 1;
    case DRIVER_RECEIVED = 2;
    case ON_ITS_WAY = 3;
    case DELIVERED = 4;
    case CANCELLED = 99;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CANCELLED => 'Cancelled',
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::DRIVER_RECEIVED => 'Driver Received',
            self::ON_ITS_WAY => "On It's Way",
            self::DELIVERED => 'Delivered',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PENDING => 'PENDING',
            self::APPROVED => 'APPROVED',
            self::ON_ITS_WAY => 'ON_ITS_WAY',
            self::DRIVER_RECEIVED => 'DRIVER_RECEIVED',
            self::DELIVERED => 'DELIVERED',
            self::CANCELLED => 'CANCELLED',
        };
    }
}
