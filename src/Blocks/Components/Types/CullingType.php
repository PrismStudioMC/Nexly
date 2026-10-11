<?php

namespace Nexly\Blocks\Components\Types;

enum CullingType: string
{
    case FENCE = "nexly:culling.fence";
    case TRAPDOOR = "nexly:culling.trapdoor";
    case GLASS = "nexly:culling.glass";

    /**
     * Returns the enum value as a string.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }
}
