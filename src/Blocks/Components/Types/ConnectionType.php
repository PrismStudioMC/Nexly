<?php

namespace Nexly\Blocks\Components\Types;

enum ConnectionType: string
{
    case ALL = "all";
    case NONE = "none";
    case ONLY_FENCES = "only_fences";

    public function getValue(): string
    {
        return $this->value;
    }
}
