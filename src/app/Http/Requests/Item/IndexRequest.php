<?php

declare(strict_types=1);

namespace App\Http\Requests\Item;

use App\Models\Item;
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
     * ソート対象のカラムを返す。nullならデフォルト値の'id'を返す。
     *
     * @return string
     */
    public function getSortColumn(): string
    {
        $columns = Item::SORTABLE_COLUMNS;

        $key = array_search($this->column, $columns, true);

        if (! $key) {
            return 'id';
        }

        return $columns[$key];
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
