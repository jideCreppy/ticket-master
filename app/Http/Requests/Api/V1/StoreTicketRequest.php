<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.title' => ['required', 'string', 'max:255'],
            'data.attributes.description' => ['required', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'string', 'in:A,C,H,X,O'],
            'data.relationships.author.data.id' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'data.attributes.status.in' => 'The status must be one of the following: A, C, H, X, O.',
        ];
    }
}
