<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ProjectileItemComponent extends DataDrivenItemComponent
{
    /**
     * @param float $minimumCriticalPower
     * @param string $entity
     */
    public function __construct(
        private readonly string $entity = "",
        private readonly float $minimumCriticalPower = 1.25,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::PROJECTILE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("projectile_entity", new StringTag($this->entity))
            ->setTag("minimum_critical_power", new FloatTag($this->minimumCriticalPower));
    }
}
