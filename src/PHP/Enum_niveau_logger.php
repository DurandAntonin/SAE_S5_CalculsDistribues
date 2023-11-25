<?php

namespace PHP;

/**
 * Priorités possibles pour un log
 *
 * @version 1.0
 */
enum Enum_niveau_logger : int
{
    case DEBUG = 1;

    case INFO = 2;

    case WARNING = 3;

    case ERROR = 4;

    case CRITICAL = 5;

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * Renvoi une valeur de l'énumération correspondant au string donné en paramètre
     *
     * @param string $name Priorité (Ex : $name = 'DEBUG')
     * @return Enum_niveau_logger
     *
     * @version 1.0
     */
    public static function fromName(string $name): Enum_niveau_logger
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}
