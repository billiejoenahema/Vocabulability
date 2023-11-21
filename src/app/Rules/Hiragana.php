<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\InvokableRule;

class Hiragana implements InvokableRule
{
    /**
     * ひらがなかどうかを判定する（スペース、タブは許可する）
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function __invoke($attribute, $value, $fail): void
    {
        if (! preg_match("/^([　 \t\r\n]|[ぁ-ん]|[ー])+$/u", $value)) {
            $fail(':attributeはひらがなで指定してください。');
        }
    }
}
