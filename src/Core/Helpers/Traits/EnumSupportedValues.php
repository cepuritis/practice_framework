<?php

namespace Core\Helpers\Traits;

/**
 * @method static cases()
 */
trait EnumSupportedValues
{
    /**
     * @return array
     */
    public static function getSupported(): array
    {
        if (!enum_exists(static::class)) {
            throw new \LogicException(
                sprintf(
                    "EnumSupportedValues can only be used on an enum, %s is not an enum",
                    static::class
                )
            );
        }

        /** @phpstan-ignore-next-line */
        return array_column(static::cases(), 'value');
    }
}
