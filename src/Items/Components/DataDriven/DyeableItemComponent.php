<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DyeableItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly string $color = "#ffffff",
    ) {
        if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            throw new \InvalidArgumentException("Invalid color format. Use hex format like #RRGGBB or #RGB.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DYEABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("default_color", new StringTag($this->color));
    }
}
