<?php

namespace Nexly\Blocks\Components\Types;

enum Face: string
{
    case DOWN = "down";
    case UP = "up";
    case NORTH = "north";
    case SOUTH = "south";
    case WEST = "west";
    case EAST = "east";

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
