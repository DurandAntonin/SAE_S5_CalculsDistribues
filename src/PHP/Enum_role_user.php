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
     * Retourne la valeur de l'instance de cette énumération sous forme de chaîne de caractères
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

    /**
     * Renvoi une valeur de l'énumération correspondant au string donné en paramètre
     *
     * @param string $name Priorité (Ex : $name = 'USER')
     * @return Enum_role_user
     *
     * @version 1.0
     */
    public static function fromName(string $name): Enum_role_user
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}
