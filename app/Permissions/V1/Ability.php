<?php

namespace App\Permissions\V1;

enum Ability: string {
    case CreateTicket = 'ticket:create';
    case UpdateTicket = 'ticket:update';
    case ReplaceTicket = 'ticket:replace';
    case DeleteTicket = 'ticket:delete';

    case UpdateOwnTicket = 'ticket:own:update';
    case DeleteOwnTicket = 'ticket:own:delete';

    case CreateUser = 'user:create';
    case UpdateUser = 'user:update';
    case ReplaceUser = 'user:replace';
    case DeleteUser = 'user:delete';
}
