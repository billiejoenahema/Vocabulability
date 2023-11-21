<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email' => ['required', 'string', 'max:255', 'email:rfc,spoof,filter'],
            'password' => ['required', 'regex:/^[¥x20-¥x7F]+$/', 'min:8', 'max:128'],
            'token' => ['required', 'string'],
        ];
    }
}
