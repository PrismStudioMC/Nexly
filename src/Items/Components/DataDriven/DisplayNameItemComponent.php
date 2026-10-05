<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DisplayNameItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DISPLAY_NAME->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new StringTag($this->name));
    }
}
