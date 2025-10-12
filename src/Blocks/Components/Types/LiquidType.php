<?php

namespace Nexly\Blocks\Components\Types;

enum LiquidType: string
{
    case WATER = "water";
    case LAVA = "lava";

    /**
     * Returns the name of the liquid type.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the string value of the liquid type.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Creates a LiquidType from a string value.
     *
     * @param string $value
     * @return LiquidType|null
     */
    public static function fromString(string $value): ?self
    {
        return match ($value) {
            self::WATER->value => self::WATER,
            self::LAVA->value => self::LAVA,
            default => null,
        };
    }
}
