<?php

declare(strict_types=1);

if (!function_exists('hello')) {
    /**
     * 文字列中の数字のみを返します
     *
     * @param string|null $value
     * @return ?string
     */
    function toNumberOnly($value): ?string
    {
        if (is_null($value)) {
            return null;
        }
        // 数字を半角に変換する
        $value = mb_convert_kana($value, "n");
        // 数字以外を取り除く
        $value = preg_replace('/[^0-9]/', '', $value);

        return $value;
    }
}
