<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class EnglishWord implements InvokableRule
{
    /**
     * 半角英数字記号のみを許可する。
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!preg_match('/^[[:graph:]|[:space:]]+$/i', $value)) {
            $fail('半角英数字記号以外は使用できません。');
        }
    }
}
