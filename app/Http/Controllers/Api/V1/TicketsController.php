<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\Filters\TicketFilter;
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
     * Return all tickets. Results can be filtered and sorted.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup  Tickets
     *
     * @queryParam filter[status] string Tickets status. Multiple values separated by comma supported. Supported status include A,C,H,X,O. Example: filter[status]=A,C,H,X,O
     * @queryParam filter[title] string Tickets name. Asterisks will be replaced with supported DB wildcards. Example: filter[title]=*support request*
     * @queryParam filter[createdAt] string Tickets created date or date range separated by comma. Example: filter[createdAt]=2020-01-01,2020-01-02
     * @queryParam filter[updatedAt] string Tickets updated date or date range separated by comma. Example: filter[updatedAt]=2020-01-01,2020-01-02
     * @queryParam sort string Sort all returned attributes. Sort direction can be specified by prepending a minus sign in front of the target field(s). Example: sort=-status,title
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(Ticket::filter($filters)->latest('id')->paginate(10));
    }

    /**
     * Create Ticket
     *
     * Create a new ticket for a specified author. Authors logged in can only save tickets belonging to themselves. Admins have full privileges and can save tickets for any author.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup  Tickets
     *
     * @responseFile 201 storage/responses/api/v1/tickets/tickets.post.json
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

        return response()->json(new TicketResource($ticket), 201);
    }

    /**
     * Get Ticket
     *
     * Return the specified ticket.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup  Tickets
     */
    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json(new TicketResource($ticket), 200);
    }

    /**
     * Update Ticket
     *
     *  Update the specified ticket. Authors logged in can only update tickets belonging to themselves. Admins have full privileges and can update tickets belonging to any author.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup  Tickets
     *
     * @responseFile 200 storage/responses/api/v1/tickets/tickets.put.json
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
     * Delete Ticket
     *
     * Delete the specified ticket. Authors logged in can only delete tickets belonging to themselves. Admins have full privileges and can delete tickets belonging to any author.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup  Tickets
     *
     * @responseFile 200 storage/responses/api/v1/misc/delete.json
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        //Policy check
        $this->applyPolicy('delete', $ticket);

        $ticket->delete();

        return $this->ok('Deleted successfully.');
    }
}
