<?php

declare(strict_types=1);

namespace App\Enums;

enum Category: string
{
    case PERSON = '01';

    /**
     * value値の配列に変換する。
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
