<?php

namespace Nexly\Blocks\Components\Types;

enum HorizontalFace: string
{
    case NORTH = "north";
    case SOUTH = "south";
    case EAST = "east";
    case WEST = "west";

    public function getValue(): string
    {
        return $this->value;
    }
}
