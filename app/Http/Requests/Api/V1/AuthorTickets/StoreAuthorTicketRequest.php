<?php

namespace App\Http\Requests\Api\V1\AuthorTickets;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorTicketRequest extends FormRequest
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
            'data.attributes.title' => ['required', 'string', 'max:255'],
            'data.attributes.description' => ['required', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'string', 'in:A,C,H,X,O'],
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'data.attributes.status' => 'The status must be one of the following: A, C, H, X, O.',
        ];
    }
}
