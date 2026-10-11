<?php

namespace Nexly\Blocks\Traits;

enum TraitStateId: string
{
    case CARDINAL_DIRECTION = 'cardinal_direction';
    case FACING_DIRECTION = 'facing_direction';
    case BLOCK_FACE = 'block_face';
    case VERTICAL_HALF = 'vertical_half';
    case SIXTEEN_WAY_ROTATION = 'sixteen_way_rotation';
    case CORNER_AND_CARDINAL_DIRECTION = 'corner_and_cardinal_direction';

    /**
     * @return TraitId
     */
    public function getTrait(): TraitId
    {
        return match ($this) {
            self::BLOCK_FACE, self::VERTICAL_HALF => TraitId::PLACEMENT_POSITION,
            default => TraitId::PLACEMENT_DIRECTION,
        };
    }
}
