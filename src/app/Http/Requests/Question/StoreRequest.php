<?php

namespace App\Http\Requests\Question;

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
            'word' => 'required|regex:/^[a-zA-Z]*$/|max:255',
            'correct_answer' => 'required|not_regex:/^[a-zA-Z]*$/|max:255',
            'example' => 'nullable|string|max:255',
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
            'example' => '例文',
        ];
    }
}
