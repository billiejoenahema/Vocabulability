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
            'column' => 'nullable|string',
            'is_asc' => 'nullable|string',
            'keyword' => 'nullable|string',
            'filter' => 'nullable|string|regex:/[ぁ-ん]/', // ひらがな1文字
        ];
    }

    /**
     * ソートの方向を返す。
     *
     * @return string
     */
    public function sortDirection(): string
    {
        if ($this->is_asc === 'true') {
            return 'asc';
        } else {
            return 'desc';
        }
    }
}
