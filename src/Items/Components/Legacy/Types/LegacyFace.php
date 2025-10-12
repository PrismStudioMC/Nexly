<?php

namespace Nexly\Items\Components\Legacy\Types;

enum LegacyFace: string
{
    case UP = "up";
    case DOWN = "down";
    case NORTH = "north";
    case SOUTH = "south";
    case WEST = "west";
    case EAST = "east";

    /**
     * Get the string value of the enum case.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
