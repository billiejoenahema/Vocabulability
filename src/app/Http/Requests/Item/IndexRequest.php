<?php

declare(strict_types=1);

namespace App\Http\Requests\Item;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

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
            'column' => 'nullable|string',
            'is_asc' => 'nullable|string',
            'keyword' => 'nullable|string',
            'filter' => 'nullable|string|regex:/[ぁ-ん]/', // ひらがな1文字
        ];
    }

    /**
     * ソート対象のカラムを返す。nullならデフォルト値の'id'を返す。
     */
    public function getSortColumn(): string
    {
        $key = array_search($this->column, Item::SORTABLE_COLUMNS, true);

        if (! $key) {
            return 'id';
        }

        return Item::SORTABLE_COLUMNS[$key];
    }

    /**
     * ソートの方向を返す。
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
