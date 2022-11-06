<?php

namespace App\Http\Requests\Question;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

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
            'keyword' => 'nullable|string',
            'filter' => 'nullable|string|regex:/[a-z]/', // 小文字アルファベット1文字
        ];
    }

    /**
     * ソート対象のカラム名を返す
     *
     * @return string|null
     */
    public function getSortColumn(): ?string
    {
        $key = $this->column;
        $columns = [
            'word',
            'correct_answer',
        ];
        $column = Arr::first($columns, function ($value) use ($key) {
            return $value === $key;
        }, null);

        return $column;
    }

    /**
     * ソートの方向を返す。
     *
     * @return string
     */
    public function getSortDirection(): string
    {
        if ($this->is_asc === 'true') {
            return 'asc';
        } else {
            return 'desc';
        }
    }
}
