<?php

namespace App\Http\Requests\Api\V1\Tickets;

use App\Http\Requests\Api\V1\ApiFormRequests;

class StoreTicketRequest extends ApiFormRequests
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
        $rules = [
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.relationships' => ['required', 'array'],
            'data.relationships.author' => ['required', 'array'],
            'data.relationships.author.data' => ['required', 'array'],
            'data.attributes.title' => ['required', 'string', 'max:255'],
            'data.attributes.description' => ['required', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'string', 'in:A,C,H,X,O'],
            'data.relationships.author.data.id' => ['prohibited'],
        ];

        if ($this->canCreateOwn()) {
            if (auth()->user()->id == request()->all()['data']['relationships']['author']['data']['id']) {
                $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id', 'size:'.auth()->user()->id];
            }
        }

        if ($this->canCreateAny()) {
            $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'data.attributes.status' => 'The status must be one of the following: A, C, H, X, O.',
            'data.relationships.author.data.id.prohibited' => 'Prohibited operation.',
        ];
    }

    public function bodyParameters()
    {
        return [
            'data.attributes.title' => [
                'type' => 'string',
                'description' => 'The title of the ticket.',
                'example' => 'Support Request',
            ],
            'data.attributes.description' => [
                'type' => 'string',
                'description' => 'The description of the ticket.',
                'example' => 'New ticket for support.',
            ],
            'data.attributes.status' => [
                'type' => 'string',
                'description' => 'The status of the ticket.',
                'example' => 'A',
            ],
            'data.relationships.author.data.id' => [
                'type' => 'integer',
                'description' => 'The authors id related to the ticket.',
                'example' => 1,
            ],
        ];
    }
}
