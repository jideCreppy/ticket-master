<?php

namespace App\Policies\V1;

use App\Http\Permissions\V1\Abilities;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    //    /**
    //     * Determine whether the user can view any models.
    //     */
    //    public function viewAny(User $user): bool
    //    {
    //        //
    //    }
    //
    //    /**
    //     * Determine whether the user can view the model.
    //     */
    //    public function view(User $user, Ticket $ticket): bool
    //    {
    //        //
    //    }
    //
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $authorId = null;

        if (request()->routeIs('authors.tickets.*')) {
            $authorId = request()->author->id;
        } else {
            $authorId = request()->all()['data']['relationships']['author']['data']['id'];
        }

        if ($user->tokenCan(Abilities::CREATE_OWN_TICKET) && $user->id == $authorId) {
            return true;
        } elseif ($user->tokenCan(Abilities::CREATE_TICKET)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UPDATE_OWN_TICKET) && $user->id == $ticket->user_id) {
            return true;
        } elseif ($user->tokenCan(Abilities::UPDATE_TICKET)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::DELETE_OWN_TICKET) && $user->id == $ticket->user_id) {
            return true;
        } elseif ($user->tokenCan(Abilities::DELETE_TICKET)) {
            return true;
        }

        return false;
    }

    //
    //    /**
    //     * Determine whether the user can restore the model.
    //     */
    //    public function restore(User $user, Ticket $ticket): bool
    //    {
    //        //
    //    }
    //
    //    /**
    //     * Determine whether the user can permanently delete the model.
    //     */
    //    public function forceDelete(User $user, Ticket $ticket): bool
    //    {
    //        //
    //    }
}
