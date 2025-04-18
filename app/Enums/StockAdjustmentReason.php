<?php

declare(strict_types=1);

namespace App\Enums;

enum StockAdjustmentReason: string
{
    case Purchase = 'purchase';
    case Sale = 'sale';
    case Loss = 'loss';
    case Adjustment = 'adjustment';
    case Return = 'return';
}
