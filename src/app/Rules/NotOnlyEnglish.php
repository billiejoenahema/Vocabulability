<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class NotOnlyEnglish implements InvokableRule
{
    /**
     * 英字のみは許可しない。
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (preg_match('/^[a-zA-Z]*$/', $value)) {
            $fail('英字のみでは登録できません。');
        }
    }
}
