<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum ItemCooldownType: string
{
    case NONE = "none";
    case CHORUS = "chorusfruit";

    /**
     * Get the string value of the cooldown type.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
