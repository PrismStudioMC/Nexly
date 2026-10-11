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
}
