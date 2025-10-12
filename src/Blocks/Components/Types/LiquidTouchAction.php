<?php

namespace Nexly\Blocks\Components\Types;

enum LiquidTouchAction: string
{
    case BROKEN = "broken";
    case POPPED = "popped";
    case BLOCKING = "blocking";
    case NO_REACTION = "no_reaction";

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
            self::BROKEN->value => self::BROKEN,
            self::POPPED->value => self::POPPED,
            self::BLOCKING->value => self::BLOCKING,
            self::NO_REACTION->value => self::NO_REACTION,
            default => null,
        };
    }
}
