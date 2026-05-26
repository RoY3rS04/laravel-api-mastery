<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities {
    public static function getAbilities(User $user) {
        if ($user->is_manager) {
            return Ability::cases();
        }

        return [
            Ability::CreateTicket,
            Ability::UpdateOwnTicket,
            Ability::DeleteOwnTicket
        ];
    }
}
