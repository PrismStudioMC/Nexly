<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CooldownItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $duration,
        private readonly string $category = ""
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::COOLDOWN->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("duration", new FloatTag($this->duration))
            ->setTag("category", new StringTag($this->category));
    }
}
