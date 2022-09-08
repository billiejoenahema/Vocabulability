<?php

namespace App\Http\Requests\Item;

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
            'name_kana' => 'required|string|max:50',
            'category' => 'required|string|max:2', // TODO CategoryEnum に存在する value のみ許可する
            'precedents' => 'required|array',
            'precedents.*.name' => 'required|string|max:50',
        ];
    }
}
