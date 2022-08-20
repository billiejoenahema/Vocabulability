<?php

namespace App\Http\Requests\Question;

use App\Rules\EnglishWord;
use App\Rules\NotOnlyEnglish;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'word' => ['required', 'max:255', 'unique:questions', new EnglishWord],
            'correct_answer' => ['required', 'max:255', new NotOnlyEnglish],
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
            'word' => '単語',
            'correct_answer' => '正解',
        ];
    }
}
