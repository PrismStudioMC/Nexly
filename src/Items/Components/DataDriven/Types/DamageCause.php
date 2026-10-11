<?php

namespace Nexly\Items\Components\DataDriven\Types;

class DamageCause
{
    public const ALL = "all";
    public const FIRE = "fire";
    public const LAVA = "lava";
    public const DROWNING = "drowning";
    public const FALL = "fall";
    public const BLOCK_EXPLOSION = "block_explosion";
    public const ENTITY_EXPLOSION = "entity_explosion";
    public const CAMPFIRE = "campfire";
    public const CHARGING = "charging";
    public const MAGIC = "magic";
    public const WITHER = "wither";
    public const STARVE = "starve";
    public const SUFFOCATION = "suffocation";
    public const VOID = "void";
    public const CONTACT = "contact";
    public const PROJECTILE = "projectile";
    public const ENTITY_ATTACK = "entity_attack";
    public const ANVIL = "anvil";
    public const FALLING_BLOCK = "falling_block";
    public const THORNS = "thorns";
    public const FIRE_TICK = "fire_tick";
    public const FIREWORKS = "fireworks";
    public const FLY_INTO_WALL = "fly_into_wall";
    public const FREEZING = "freezing";
    public const LIGHTNING = "lightning";
    public const MACE_SMASH = "mace_smash";
    public const MAGMA = "magma";
    public const NONE = "none";
    public const OVERRIDE = "override";
    public const PISTON = "piston";
    public const RAM_ATTACK = "ram_attack";
    public const SELF_DESTRUCT = "self_destruct";
    public const SONIC_BOOM = "sonic_boom";
    public const SOUL_CAMPFIRE = "soul_campfire";
    public const STALACTITE = "stalactite";
    public const STALAGMITE = "stalagmite";
    public const TEMPERATURE = "temperature";

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
