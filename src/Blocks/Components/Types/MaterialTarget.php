<?php

namespace Nexly\Blocks\Components\Types;

enum MaterialTarget: string
{
    case ALL = "*";
    case SIDES = "sides";
    case UP = "up";
    case DOWN = "down";
    case NORTH = "north";
    case EAST = "east";
    case SOUTH = "south";
    case WEST = "west";

    /**
     * Returns the name of the material target.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the string value of the material target.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Creates a MaterialTarget from a string value.
     *
     * @param string $value
     * @return MaterialTarget|null
     */
    public static function fromString(string $value): ?self
    {
        return match ($value) {
            self::TARGET_ALL->value => self::TARGET_ALL,
            self::TARGET_SIDES->value => self::TARGET_SIDES,
            self::TARGET_UP->value => self::TARGET_UP,
            self::TARGET_DOWN->value => self::TARGET_DOWN,
            self::TARGET_NORTH->value => self::TARGET_NORTH,
            self::TARGET_EAST->value => self::TARGET_EAST,
            self::TARGET_SOUTH->value => self::TARGET_SOUTH,
            self::TARGET_WEST->value => self::TARGET_WEST,
            default => null,
        };
    }
}
