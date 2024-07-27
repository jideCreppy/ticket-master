<?php

namespace App\Http\Requests\Api\V1\Tickets;

use App\Http\Permissions\V1\Abilities;
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
        $rules = [
            'data.attributes.title' => ['sometimes', 'string', 'max:255'],
            'data.attributes.description' => ['sometimes', 'string', 'max:255'],
            'data.attributes.status' => ['required', 'sometimes', 'in:A,C,H,X,O'],
            'data.relationships.author.data.id' => ['prohibited'],
        ];

        if (auth()->user()->tokenCan(Abilities::UPDATE_OWN_TICKET)) {
            if (auth()->user()->id == $this->ticket->user_id) {
                $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id', 'size:'.auth()->user()->id];
            }
        }

        if (auth()->user()->tokenCan(Abilities::UPDATE_TICKET)) {
            $rules['data.relationships.author.data.id'] = ['required', 'integer', 'exists:users,id'];
        }

        return $rules;
    }
}
