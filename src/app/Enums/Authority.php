<?php

declare(strict_types=1);

namespace App\Enums;

enum Authority: int
{
    case ADMIN = 1;
    case GENERAL = 0;

    /**
     * @return string
     */
    public function text(): string
    {
        return match ($this) {

            self::ADMIN => '管理者',
            self::GENERAL => '一般',
        };
    }
}
