<?php

declare(strict_types=1);

namespace App\Enums;

enum PageType: string
{
    case HOME = 'home';
    case ABOUT = 'about';
    case BLOG = 'blog';
    case CONTACT = 'contact';
    case SERVICE = 'service';
    case MARKETING_PRODUCT = 'marketing_product';
    case POPULAR_COMBINATIONS = 'popular_combinations';
    case COMPOSE_CTA = 'compose_cta';

    public static function options(): array
    {
        return self::cases();
    }

    public static function getLabel($value): ?string
    {
        foreach (self::cases() as $case) {
            if ($case->getValue() === $value) {
                return $case->label();
            }
        }

        return null;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public function label(): string
    {
        return match ($this) {
            static::HOME => __('Home'),
            static::ABOUT => __('About'),
            static::BLOG => __('blog'),
            static::CONTACT => __('contact'),
            static::SERVICE => __('service'),
            static::MARKETING_PRODUCT => __('marketing_product'),
            static::POPULAR_COMBINATIONS => __('popular_combinations'),
            static::COMPOSE_CTA => __('compose_cta'),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }

    // loop through the values:

    // @foreach(App\Enums\PageType::values() as $key=>$value)
    //     <option value="{{ $key }}">{{ $value }}</option>
    // @endforeach
}
