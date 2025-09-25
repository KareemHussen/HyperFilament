<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PackageTypeEnum: int implements HasLabel
{
    case Box = 0;
    case Pallets = 1;
    case Other = 99;

    public function getLabel(): ?string
    {
        return $this->name;
    }

    
}
