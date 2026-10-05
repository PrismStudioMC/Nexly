<?php

namespace Nexly\Blocks\Components\Types;

enum PrecipitationType
{
    case OBRAIN;
    case OBSTRUCT_RAIN_ACCUMULATE_SNOW;
    case ACCUMULATE_SNOW;
    case NONE;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
