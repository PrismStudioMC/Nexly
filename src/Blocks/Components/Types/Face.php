<?php

namespace Nexly\Blocks\Components\Types;

class Face
{
    public const DOWN = "down";
    public const UP = "up";
    public const NORTH = "north";
    public const SOUTH = "south";
    public const WEST = "west";
    public const EAST = "east";

    public const ALL = [
        self::DOWN,
        self::UP,
        self::NORTH,
        self::SOUTH,
        self::WEST,
        self::EAST,
    ];
}
