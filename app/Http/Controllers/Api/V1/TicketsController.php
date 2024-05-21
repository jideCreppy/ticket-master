<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\API\V1\StoreTicketRequest;
use App\Http\Requests\API\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class TicketsController extends APIController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request): TicketResource|JsonResponse
    {
        try {
            User::findOrFail(request('data.relationships.author.data.id'));
        } catch (ModelNotFoundException $exception) {
            return $this->ok('', [
                'message' => 'The user with id '.request('data.relationships.author.data.id').' does not exist.',
                'statusCode' => 404]
            );
        }

        $ticket = Ticket::create([
            'user_id' => request('data.relationships.author.data.id'),
            'title' => request('data.attributes.title'),
            'description' => request('data.attributes.description'),
            'status' => request('data.attributes.status'),
        ]);

        return new TicketResource($ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
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
