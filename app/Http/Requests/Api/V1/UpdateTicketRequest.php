<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'data.attributes.title' => ['sometimes', 'string', 'max:255'],
            'data.attributes.description' => ['sometimes', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'sometimes', 'in:A,C,H,X,O'],
            'data.relationships.author.data.id' => ['required', 'integer'],
        ];
    }
}
