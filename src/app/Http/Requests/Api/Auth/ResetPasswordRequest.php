<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email' => ['required', 'string', 'max:255', 'email:rfc,spoof'],
            'password' => ['required', 'regex:/^[¥x20-¥x7F]+$/', 'min:8', 'max:128'],
            'token' => ['required', 'string'],
        ];
    }
}
