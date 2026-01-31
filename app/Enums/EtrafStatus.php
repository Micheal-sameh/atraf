<?php

namespace App\Enums;

enum EtrafStatus: string
{
    case PENDING = 1;
    case WAITING = 2;
    case COMPLETED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('messages.pending'),
            self::WAITING => __('messages.waiting'),
            self::COMPLETED => __('messages.completed'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::WAITING => 'info',
            self::COMPLETED => 'success',
        };
    }

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
