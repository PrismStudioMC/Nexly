<?php

namespace Nexly\Items\Components\Legacy\Types;

enum LegacyItemUseActionType: int
{
    case CHORUS_TELEPORT = 0;
    case SUSPICIOUS_STEW = 1;
    case NONE = 2;

    /**
     * Get the integer value of the use action type.
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
