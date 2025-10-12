<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum DamageCause: string
{
    case ALL = "all";
    case FIRE = "fire";
    case LAVA = "lava";
    case DROWNING = "drowning";
    case FALL = "fall";
    case CACTUS = "cactus";
    case EXPLOSION = "explosion";
    case MAGIC = "magic";
    case WITHER = "wither";
    case STARVE = "starve";
    case POISON = "poison";
    case SUFFOCATION = "suffocation";
    case VOID = "void";
    case CONTACT = "contact";
    case PROJECTILE = "projectile";
    case MOB = "mob";
    case PLAYER = "player";
    case ANVIL = "anvil";
    case FALLING_BLOCK = "falling_block";
    case THORNS = "thorns";
    case DRAGON_BREATH = "dragon_breath";
    case CUSTOM = "custom";

    /**
     * Get the string value of the damage cause.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
