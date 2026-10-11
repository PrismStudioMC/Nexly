<?php

namespace Nexly\Blocks\Components\Types;

enum PlacementFace: string
{
    case DOWN = 'down';
    case UP = 'up';
    case NORTH = 'north';
    case SOUTH = 'south';
    case WEST = 'west';
    case EAST = 'east';
    case SIDE = 'side';
    case ALL = 'all';
}
