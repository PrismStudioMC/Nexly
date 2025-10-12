<?php

namespace Nexly\Blocks\Components\Types;

enum BreathabilityType: string
{
    case SOLID = "solid";
    case AIR = "air";

    /**
     * Returns the name of the breathability type.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the string value of the breathability type.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Creates a BreathabilityType from a string value.
     *
     * @param string $value
     * @return BreathabilityType|null
     */
    public static function fromString(string $value): ?self
    {
        return match ($value) {
            self::SOLID->value => self::SOLID,
            self::AIR->value => self::AIR,
            default => null,
        };
    }
}
