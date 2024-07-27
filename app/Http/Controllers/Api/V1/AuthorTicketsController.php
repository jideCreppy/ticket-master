<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\Api\V1\TicketResource;
use App\Models\Ticket;

class AuthorTicketsController extends APIController
{
    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $author_id)->filter($filters)->paginate()
        );
    }
}
