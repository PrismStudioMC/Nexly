<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

/** @deprecated */
#[Attribute(Attribute::TARGET_CLASS)]
class ChargeableItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $movementModifier,
        private readonly string $onComplete = "",
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::CHARGEABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("movement_modifier", new FloatTag($this->movementModifier))
            ->setTag("on_complete", new StringTag($this->onComplete));
    }
}
