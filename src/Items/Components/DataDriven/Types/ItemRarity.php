<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum ItemRarity: string
{
    case COMMON = "common";
    case UNCOMMON = "uncommon";
    case RARE = "rare";
    case EPIC = "epic";

    /**
     * The name of the rarity.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The value of the rarity.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Create an ItemRarity from a string name.
     *
     * @param string $name
     * @return self
     */
    public static function fromName(string $name): self
    {
        return match (strtolower($name)) {
            'common' => self::COMMON,
            'uncommon' => self::UNCOMMON,
            'rare' => self::RARE,
            'epic' => self::EPIC,
            default => throw new \InvalidArgumentException("Invalid item rarity name: $name"),
        };
    }
}
