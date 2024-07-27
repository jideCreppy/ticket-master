<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\AuthorTickets\StoreAuthorTicketRequest;
use App\Http\Requests\Api\V1\AuthorTickets\UpdateAuthorTicketRequest;
use App\Http\Resources\Api\V1\Tickets\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorTicketsController extends APIController
{
    protected const POLICY_CLASS = TicketPolicy::class;

    public function index(TicketFilter $filters, User $author): AnonymousResourceCollection
    {
        return TicketResource::collection($author->tickets()->filter($filters)->paginate(10));
    }

    /**
     * Store the specified authors ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function store(StoreAuthorTicketRequest $request, User $author): TicketResource
    {
        $attributes = $request->validated();

        $ticket = Ticket::create([
            'title' => $attributes['data']['attributes']['title'],
            'description' => $attributes['data']['attributes']['description'],
            'status' => $attributes['data']['attributes']['status'],
        ]);

        return new TicketResource($ticket);
    }

    /**
     * Update the specified authors ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function update(UpdateAuthorTicketRequest $request, User $author, Ticket $ticket): TicketResource
    {
        $attributes = $request->validated();

        //Policy check
        $this->checkPermission('update', $ticket);

        $ticket->update([
            'title' => $attributes['data']['attributes']['title'],
            'description' => $attributes['data']['attributes']['description'],
            'status' => $attributes['data']['attributes']['status'],
        ]);

        return new TicketResource($ticket->fresh());
    }

    /**
     * Delete the specified authors ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function destroy(UpdateAuthorTicketRequest $request, User $author, Ticket $ticket): JsonResponse
    {
        //Policy check
        $this->checkPermission('delete', $ticket);

        $ticket->delete();

        return $this->ok('Deleted successfully.');
    }
}
