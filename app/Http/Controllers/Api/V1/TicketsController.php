<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\API\V1\StoreTicketRequest;
use App\Http\Requests\API\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\Tickets\TicketResource;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketsController extends APIController
{
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
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Delete the specified ticket.
     *
     * @authenticated
     *
     * @group Tickets
     */
    public function destroy($ticket_id): JsonResponse
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();

            return $this->ok('Ticket Deleted');

        } catch (ModelNotFoundException $exception) {
            return $this->ok('', ['message' => 'The ticket with id '.$ticket_id.' does not exist.', 'statusCode' => 404]);
        }
    }
}
