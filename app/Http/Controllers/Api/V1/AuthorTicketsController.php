<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\Filters\TicketFilter;
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

    /**
     * Get Authors Tickets
     *
     * Return all tickets belonging to the specified author. Results can be filtered and sorted.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup Author Tickets
     *
     * @queryParam filter[status] string Tickets status. Multiple values separated by comma supported. Supported status include A,C,H,X,O. Example: filter[status]=A,C,H,X,O
     * @queryParam filter[title] string Tickets name. Asterisks will be replaced with supported DB wildcards. Example: filter[title]=*support request*
     * @queryParam filter[createdAt] string Tickets created date or date range separated by comma. Example: filter[createdAt]=2020-01-01,2020-01-02
     * @queryParam filter[updatedAt] string Tickets updated date or date range separated by comma. Example: filter[updatedAt]=2020-01-01,2020-01-02
     * @queryParam sort string Sort all returned attributes. Sort direction can be specified by prepending a minus sign in front of the target field(s). Example: sort=-status,title
     */
    public function index(TicketFilter $filters, User $author): AnonymousResourceCollection
    {
        return TicketResource::collection($author->tickets()->filter($filters)->paginate(10));
    }

    /**
     * Create Author Ticket
     *
     * Create a new ticket for the specified author. Authors logged in can only save tickets belonging to themselves. Admins have full privileges and can save tickets for any author.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup Author Tickets
     *
     * @responseFile 201 storage/responses/api/v1/tickets/author_tickets.post.json
     */
    public function store(StoreAuthorTicketRequest $request, User $author): JsonResponse
    {
        $attributes = $request->validated();

        //Policy check
        $this->applyPolicy('create', Ticket::class);

        $ticket = Ticket::create([
            'title' => $attributes['data']['attributes']['title'],
            'description' => $attributes['data']['attributes']['description'],
            'status' => $attributes['data']['attributes']['status'],
            'user_id' => $author->id,
        ]);

        return response()->json(new TicketResource($ticket), status: 201);
    }

    /**
     * Update Authors Ticket
     *
     * Update the specified authors ticket. Authors logged in can only update tickets belonging to themselves. Admins have full privileges and can update tickets belonging to any author.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup Author Tickets
     *
     * @responseFile 200 storage/responses/api/v1/tickets/tickets.put.json
     */
    public function update(UpdateAuthorTicketRequest $request, User $author, Ticket $ticket): JsonResponse
    {
        $attributes = $request->validated();

        //Policy check
        $this->applyPolicy('update', $ticket);

        $ticket->update([
            'title' => $attributes['data']['attributes']['title'],
            'description' => $attributes['data']['attributes']['description'],
            'status' => $attributes['data']['attributes']['status'],
        ]);

        return response()->json(new TicketResource($ticket->fresh()), status: 200);
    }

    /**
     * Delete Authors Ticket.
     *
     * Delete the specified authors ticket. Authors logged in can only delete tickets belonging to themselves. Admins have full privileges and can delete tickets belonging to any author.
     *
     * @authenticated
     *
     * @group Tickets
     *
     * @subgroup Author Tickets
     *
     * @responseFile 200 storage/responses/api/v1/misc/delete.json
     */
    public function destroy(User $author, Ticket $ticket): JsonResponse
    {
        //Policy check
        $this->applyPolicy('delete', $ticket);

        $ticket->delete();

        return $this->ok('Deleted successfully.');
    }
}
