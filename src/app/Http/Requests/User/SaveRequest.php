<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|string|max:50',
            'kana_name' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date:Y-m-d',
            'gender' => 'nullable',
            'phone' => 'nullable|string|max:14|regex:/^[0-9]+$/',
            'postcode' => 'nullable|string|max:7|regex:/^[0-9]+$/',
            'address' => 'nullable|string|max:50',
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
            'name' => '名前',
            'kana_name' => 'フリガナ',
            'birth_date' => '生年月日',
            'gender' => '性別',
            'phone' => '電話番号',
            'postcode' => '郵便番号',
            'address' => '住所',
        ];
    }

    /**
     * バリーデーションのためにデータを準備
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // 郵便番号、電話番号のハイフンを取り除く
        $this->merge([
            'postcode' => toNumberOnly($this->postcode),
            'phone' => toNumberOnly($this->phone),
        ]);
    }
}
