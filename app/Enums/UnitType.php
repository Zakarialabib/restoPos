<?php

declare(strict_types=1);

namespace App\Enums;

enum UnitType: int
{
    case Gram = 1;
    case Litre = 2;
    case Mililitre = 3;
    case Kilo = 4;
    case Piece = 5;
    case Units = 6;

    public function label(): string
    {
        return match ($this) {
            self::Gram => __('Gram'),
            self::Litre => __('Litre'),
            self::Mililitre => __('Mililitre'),
            self::Kilo => __('Kilo'),
            self::Piece => __('Piece'),
            self::Units => __('Units'),
        };
    }

    public function value(): string
    {
        return match ($this) {
            self::Gram => __('g'),
            self::Litre => __('l'),
            self::Mililitre => __('ml'),
            self::Kilo => __('kg'),
            self::Piece => __('pcs'),
            self::Units => __('units'),
        };
    }
}
