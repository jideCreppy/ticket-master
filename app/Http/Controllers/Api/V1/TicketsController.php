<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\Tickets\StoreTicketRequest;
use App\Http\Requests\Api\V1\Tickets\UpdateTicketRequest;
use App\Http\Resources\Api\V1\Tickets\TicketResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketsController extends APIController
{
    protected const POLICY_CLASS = TicketPolicy::class;

    /**
     * Show all tickets.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(Ticket::filter($filters)->latest('id')->paginate(10));
    }

    /**
     * Store a new ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function store(StoreTicketRequest $request): TicketResource|JsonResponse
    {
        //Policy check
        $this->applyPolicy('create', Ticket::class);

        $attributes = $request->validated();
        $authorId = $attributes['data']['relationships']['author']['data']['id'];

        $ticket = Ticket::create([
            'user_id' => $authorId,
            'title' => $attributes['data']['attributes']['title'],
            'description' => $attributes['data']['attributes']['description'],
            'status' => $attributes['data']['attributes']['status'],
        ]);

        return new TicketResource($ticket);
    }

    /**
     * Show the specified ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function show(Ticket $ticket): TicketResource
    {
        return new TicketResource($ticket);
    }

    /**
     * Update the specified ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): TicketResource
    {
        $attributes = $request->validated();

        //Policy check
        $this->applyPolicy('update', $ticket);

        $ticket->update([
            'title' => $attributes['data']['attributes']['title'],
            'description' => $attributes['data']['attributes']['description'],
            'status' => $attributes['data']['attributes']['status'],
            'user_id' => $attributes['data']['relationships']['author']['data']['id'],
        ]);

        return new TicketResource($ticket->fresh());
    }

    /**
     * Delete the specified ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        //Policy check
        $this->applyPolicy('delete', $ticket);

        $ticket->delete();

        return $this->ok('Deleted successfully.');
    }
}
