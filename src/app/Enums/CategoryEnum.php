<?php

namespace App\Enums;

enum CategoryEnum: string
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
