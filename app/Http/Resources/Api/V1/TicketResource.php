<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'type' => 'tickets',
            'attributes' => [
                'title' => $this->title,
                'description' => $this->when($request->routeIs('tickets.show'), $this->description),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' =>[
                    'id' => $this->user_id,
                    'type' => 'User',
                ],
                'links' => [
                    'self' => route('authors.show', ['author' => $this->user_id])
                ]
            ],
            'includes' => new UserResource($this->whenLoaded('author')),
            'links' => [
                'self' => route('tickets.show', ['ticket' => $this->id]),
            ],
        ];
    }
}
