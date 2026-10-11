<?php

namespace Nexly\Blocks\Components\Types;

enum ChestObstructionRule: string
{
    case ALWAYS = "always";
    case NEVER = "never";
    case SHAPE = "shape";

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
