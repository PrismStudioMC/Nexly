<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_kinetic_weapon_kinetic_effect_conditions?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class KineticWeaponKineticEffectConditionsItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly int $maxDuration = -1,
        private readonly float $minRelativeSpeed = 0,
        private readonly float $minSpeed = 0,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::KINETIC_WEAPON_KINETIC_EFFECT_CONDITIONS->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_duration", new IntTag($this->maxDuration))
            ->setTag("min_relative_speed", new FloatTag($this->minRelativeSpeed))
            ->setTag("min_speed", new FloatTag($this->minSpeed));
    }
}
