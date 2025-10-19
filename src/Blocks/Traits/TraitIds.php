<?php

namespace Nexly\Blocks\Traits;

enum TraitIds: string
{
    case PLACEMENT_DIRECTION = "minecraft:placement_direction";
    case PLACEMENT_POSITION = "minecraft:placement_position";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}