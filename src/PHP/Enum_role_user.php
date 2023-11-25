<?php

namespace PHP;

/**
 * Liste des rôles possibles pour un utilisateur de l'application. <br>
 * Permet de définir les accès d'un utilisateur en fonction de son rôle.
 *
 * @version 1.0
 */
enum Enum_role_user
{
    case USER;

    case ADMIN;

    case VISITEUR;

    case NULL;

    /**
     * Retourne la valeur de l'instance de cette énumération.
     *
     * @return string
     *
     * @version 1.0
     */
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
