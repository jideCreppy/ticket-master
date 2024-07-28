<?php

namespace App\Http\Requests\Api\V1\Tickets;

use App\Http\Requests\Api\V1\ApiFormRequests;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateTicketRequest extends ApiFormRequests
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
        $rules = [
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.attributes.title' => ['sometimes', 'string', 'max:255'],
            'data.attributes.description' => ['sometimes', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'sometimes', 'in:A,C,H,X,O'],
            'data.relationships' => ['required', 'array'],
            'data.relationships.author' => ['required', 'array'],
            'data.relationships.author.data' => ['required', 'array'],
            'data.relationships.author.data.id' => ['prohibited'],
        ];

        if ($this->canUpdateOwn()) {
            if (auth()->user()->id == $this->ticket->user_id) {
                $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id', 'size:'.auth()->user()->id];
            }
        }

        if ($this->canUpdateAny()) {
            $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }
}
