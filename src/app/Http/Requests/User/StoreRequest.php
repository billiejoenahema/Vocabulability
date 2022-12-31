<?php

namespace App\Http\Requests\User;

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
            'name' => 'required|string|max:50',
            'kana_name' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date:Y-m-d',
            'gender' => 'nullable',
            'phone' => 'nullable|string|max:14',
            'postcode' => 'nullable|string|max:7',
            'address' => 'nullable|string|max:50',
        ];
    }
}
