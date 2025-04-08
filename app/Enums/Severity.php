<?php

declare(strict_types=1);

namespace App\Enums;

enum Severity: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';
}
