<?php

namespace App\Enums;

enum Unit: int
{
    case Gram = 1;
    case Litre = 2;
    case Mililitre = 3;
    case Kilo = 4;
    case Piece = 5;

    public function label(): string
    {
        return match ($this) {
            self::Gram => __('Gram'),
            self::Litre => __('Litre'),
            self::Mililitre => __('Mililitre'),
            self::Kilo => __('Kilo'),
            self::Piece => __('Piece'),
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
        };
    }
}
