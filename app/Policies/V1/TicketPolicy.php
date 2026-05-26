<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Ability;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Ticket $ticket): bool {

        if ($user->tokenCan(Ability::UpdateTicket->value)) {
            return true;
        }

        return $user->tokenCan(Ability::UpdateOwnTicket->value)
            && $user->id === $ticket->user_id;
    }

    public function delete(User $user, Ticket $ticket): bool {

        if ($user->tokenCan(Ability::DeleteTicket->value)) {
            return true;
        }

        return $user->tokenCan(Ability::DeleteOwnTicket->value)
            && $user->id === $ticket->user_id;
    }

    public function replace(User $user, Ticket $ticket): bool {
        if ($user->tokenCan(Ability::ReplaceTicket->value)) {
            return true;
        }

        return false;
    }

    public function store(User $user, Ticket $ticket): bool {
        if ($user->tokenCan(Ability::CreateTicket->value)) {
            return true;
        }

        return false;
    }
}
