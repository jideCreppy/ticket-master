<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\APIController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\Api\V1\Tickets\TicketResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorTicketsController extends APIController
{
    public function index(TicketFilter $filters, User $author): AnonymousResourceCollection
    {
        return TicketResource::collection($author->tickets()->filter($filters)->paginate(10));
    }
}
