<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum ItemAnimation: int
{
    case NONE = 0;
    case EAT = 1;
    case DRINK = 2;
    case BLOCK = 3;
    case BOW = 4;
    case CAMERA = 5;
    case SPEAR = 6;
    case CROSSBOW = 9;
    case SPYGLASS = 10;
    case BRUSH = 12;

    /**
     * The name of the ItemAnimation.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the integer value of the ItemAnimation.
     *
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Create an ItemAnimation from an integer value.
     *
     * @param int $value
     * @return ItemAnimation
     */
    public static function fromInt(int $value): ItemAnimation
    {
        return match ($value) {
            1 => self::EAT,
            2 => self::DRINK,
            default => self::NONE,
        };
    }
}
