<?php

namespace PHP;

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

    public static function fromName($name): Enum_niveau_logger
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }
}
