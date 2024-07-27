<?php

namespace App\Http\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const CREATE_USER = 'create:user';

    public const UPDATE_USER = 'update:user';

    public const DELETE_USER = 'delete:user';

    public const CREATE_TICKET = 'create:ticket';

    public const UPDATE_TICKET = 'update:ticket';

    public const DELETE_TICKET = 'delete:ticket';

    public const CREATE_OWN_TICKET = 'create:own:ticket';

    public const UPDATE_OWN_TICKET = 'update:own:ticket';

    public const DELETE_OWN_TICKET = 'delete:own:ticket';

    /**
     * @return string[]
     */
    public static function getAbilities(User $user): array
    {
        if ($user->is_admin) {
            return [
                self::CREATE_USER,
                self::UPDATE_USER,
                self::DELETE_USER,
                self::CREATE_TICKET,
                self::UPDATE_TICKET,
                self::DELETE_TICKET,
            ];
        } else {
            return [
                self::CREATE_OWN_TICKET,
                self::UPDATE_OWN_TICKET,
                self::DELETE_OWN_TICKET,
            ];
        }
    }
}
