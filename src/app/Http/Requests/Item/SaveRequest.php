<?php

namespace App\Http\Requests\Item;

use App\Enums\CategoryEnum;
use App\Rules\EnglishWord;
use App\Rules\NotOnlyEnglish;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50', Rule::unique('items')->ignore($this->id), new NotOnlyEnglish],
            'name_kana' => ['required', 'string', 'max:50', 'regex:/^[ぁ-ん]+$/'],
            'category' => ['required', 'string', Rule::in(CategoryEnum::values())],
            'precedents' => 'required|array',
            'precedents.*.name' => ['required', 'string', 'max:50', new EnglishWord],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
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
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name_kana.regex' => 'ふりがなには、ひらがなを指定してください。',
        ];
    }
}
