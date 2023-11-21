<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'file' => 'required|mimes:csv',
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     */
    public function attributes(): array
    {
        return [
            'file' => 'ファイル',
        ];
    }

    /**
     * 定義済みバリデーションルールのエラーメッセージ取得
     */
    public function messages(): array
    {
        return [
            'file.required' => 'ファイルを指定してください。',
            'file.mimes' => 'CSVファイルを指定してください。',
        ];
    }
}
