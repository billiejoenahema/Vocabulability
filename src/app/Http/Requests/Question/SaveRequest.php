<?php

declare(strict_types=1);

namespace App\Http\Requests\Question;

use App\Rules\EnglishWord;
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
            'word' => ['required', 'string', 'max:255', Rule::unique('questions')->ignore($this->id), new EnglishWord],
            'correct_answer' => ['required', 'string', 'max:255', new NotOnlyEnglish],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     */
    public function attributes(): array
    {
        return [
            'word' => '単語',
            'correct_answer' => '正解',
        ];
    }
}
