<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:20'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:3', 'max:20', 'confirmed'],
            'password_confirmation' => ['required', 'min:3', 'max:20'],
        ];
    }
}
