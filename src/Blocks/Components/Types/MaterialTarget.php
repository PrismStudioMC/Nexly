<?php

namespace Nexly\Blocks\Components\Types;

enum MaterialTarget: string
{
    case ALL = "*";
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
            self::ALL->value => self::ALL,
            self::UP->value => self::UP,
            self::DOWN->value => self::DOWN,
            self::NORTH->value => self::NORTH,
            self::EAST->value => self::EAST,
            self::SOUTH->value => self::SOUTH,
            self::WEST->value => self::WEST,
            default => null,
        };
    }
}
