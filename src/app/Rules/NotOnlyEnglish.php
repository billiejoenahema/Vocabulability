<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class NotOnlyEnglish implements InvokableRule
{
    /**
     * 英字のみは許可しない。
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function __invoke($attribute, $value, $fail): void
    {
        $attribute = match ($attribute) {
            'correct_answer' => '正解',
            'name' => '項目名',
            default => 'この項目',
        };

        if (preg_match('/^[a-zA-Z]*$/', $value)) {
            $fail("{$attribute}は英字のみでは登録できません。");
        }
    }
}
