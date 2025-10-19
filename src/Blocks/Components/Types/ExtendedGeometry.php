<?php

namespace Nexly\Blocks\Components\Types;

enum ExtendedGeometry: string
{
    case CROP = "geometry.crop";
    case NETHER_WART = "geometry.nether_wart";
    case SLAB = "geometry.slab";
    case DOOR = "geometry.door";
    case FENCE = "geometry.fence";
    case FENCE_GATE = "geometry.fence_gate";
    case WALL = "geometry.wall";
    case TRAPDOOR = "geometry.trapdoor";
    case HOPPER = "geometry.hopper";
    case MOBHEAD = "geometry.mob_head";
    case LADDER = "geometry.ladder";
    case FARMLAND = "geometry.farmland";
    case GLASS_PANE = "geometry.glass_pane";

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
