<?php

namespace App\Enums;

enum AuthorityEnum: int
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
