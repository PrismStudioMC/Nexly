<?php

namespace Nexly\Blocks\Components\Types;

enum ExtendedGeometry: string
{
    case CROP = "geometry.crop";
    case NETHER_WART = "geometry.nether_wart";
    case STAIR = "geometry.stair";
    case SLAB = "geometry.slab";
    case DOOR = "geometry.door";
    case FENCE = "geometry.fence";
    case FENCE_GATE = "geometry.fence_gate";
    case WALL = "geometry.wall";
    case TRAPDOOR = "geometry.trapdoor";
    case HOPPER = "geometry.hopper";
    case HEAD_BLOCK = "geometry.head_block";
    case LADDER = "geometry.ladder";
    case FARMLAND = "geometry.farmland";
    case GLASS_PANE = "geometry.glass_pane";
    case LEVER = "geometry.lever";
    case GLASS = "geometry.glass";

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
