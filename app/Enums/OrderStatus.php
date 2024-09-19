<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: int
{
    case Pending = 0;
    case Processing = 1;
    case Completed = 2;
    case Cancelled = 3;
}
