<?php

namespace Nexly\Blocks\Traits;

enum TraitIds: string
{
    case PLACEMENT_DIRECTION = "minecraft:placement_direction";
    case PLACEMENT_POSITION = "minecraft:placement_position";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return self
     */
    public static function fromString(string $value): self
    {
        return match ($value) {
            "minecraft:placement_direction" => self::PLACEMENT_DIRECTION,
            "minecraft:placement_position" => self::PLACEMENT_POSITION,
            default => throw new \InvalidArgumentException("Invalid TraitIds value: " . $value),
        };
    }
}
