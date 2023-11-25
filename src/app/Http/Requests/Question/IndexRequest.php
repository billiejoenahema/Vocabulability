<?php

declare(strict_types=1);

namespace App\Http\Requests\Question;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class IndexRequest extends FormRequest
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
            'keyword' => 'nullable|string',
            'filter' => 'nullable|string|regex:/[a-z]/', // 小文字アルファベット1文字
        ];
    }

    /**
     * ソート対象のカラム名を返す
     */
    public function getSortColumn(): ?string
    {
        $key = $this->column;
        $column = Arr::first(Question::SORTABLE_COLUMNS, static function ($value) use ($key) {
            return $value === $key;
        }, null);

        return $column;
    }

    /**
     * ソートの方向を返す。
     */
    public function getSortOrder(): string
    {
        return $this->sort_order ?? 'desc';
    }
}
