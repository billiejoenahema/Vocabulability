<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'column' => 'nullable',
            'is_asc' => 'nullable',
            'keyword' => 'nullable',
            'filter' => 'nullable|string|regex:/[ぁ-ん]/', // ひらがな1文字
        ];
    }
}
