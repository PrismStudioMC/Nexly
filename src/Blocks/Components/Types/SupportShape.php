<?php

namespace Nexly\Blocks\Components\Types;

enum SupportShape
{
    case FENCE;
    case STAIR;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
