<?php

namespace PHP;

enum Enum_role_user
{
    case USER;

    case ADMIN;
    case VISITEUR;
    case NULL;

    public function str() : string
    {
        return match ($this) {
            Enum_role_user::USER => "USER",
            Enum_role_user::ADMIN => "ADMIN",
            Enum_role_user::VISITEUR => "VISITEUR",
            Enum_role_user::NULL => "NULL",
            default => "",
        };
    }
}
