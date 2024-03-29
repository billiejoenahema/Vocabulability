<?php

declare(strict_types=1);

namespace App\Http\Requests\Item;

use App\Enums\Category;
use App\Rules\EnglishWord;
use App\Rules\Hiragana;
use App\Rules\NotOnlyEnglish;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('items')->ignore($this->id), new NotOnlyEnglish],
            'name_kana' => ['required', 'string', 'max:50', new Hiragana],
            'category' => ['required', 'string', Rule::in(Category::values())],
            'precedents' => 'required|array',
            'precedents.*.name' => ['required', 'string', 'max:50', new EnglishWord],
            'description' => 'nullable|string|max:200'
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     */
    public function attributes(): array
    {
        return [
            'name' => '項目名',
            'name_kana' => 'ふりがな',
            'category' => 'カテゴリ',
            'precedents.*.name' => 'カラム名',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     */
    public function messages(): array
    {
        return [
            'name_kana.regex' => 'ふりがなには、ひらがなを指定してください。',
        ];
    }

    /**
     * 少なくともひとつはnullでない属性が存在するかどうか
     *
     * @param array $array
     */
    public function isInputted($array): bool
    {
        $filtered = collect($array)->filter(static function ($key, $value) {
            return filled($value);
        });

        return $filtered->count() > 0;
    }
}
