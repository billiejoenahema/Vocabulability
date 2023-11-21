<?php

declare(strict_types=1);

if (! function_exists('toNumberOnly')) {
    /**
     * 全角数字を半角に変換し、ハイフンを取り除く。
     *
     * @param string|null $value
     */
    function toNumberOnly($value): ?string
    {
        if (null === $value) {
            return null;
        }
        // 数字を半角に変換する
        $value = mb_convert_kana($value, "n");
        // ハイフンを取り除く
        $value = preg_replace('/[-－ー-−―‐ー-−ｰ]/', '', $value);

        return $value;
    }
}

if (! function_exists('toHalfWidth')) {
    /**
     * 全角数字を半角に変換する。
     *
     * @param string|null $value
     */
    function toHalfWidth($value): ?string
    {
        if (null === $value) {
            return null;
        }
        // 数字を半角に変換する
        $value = mb_convert_kana($value, "n");
        // ハイフンを取り除く
        $value = preg_replace('/[ー-−―‐ー-−ｰ]/', '', $value);

        return $value;
    }
}
