<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_piercing_weapon?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PiercingWeaponItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $hitboxMargin = 0,
        private readonly int   $min = 0,
        private readonly int   $max = 3,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::PIERCING_WEAPON->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("hitbox_margin", new IntTag($this->hitboxMargin))
            ->setTag(
                "reach",
                CompoundTag::create()
                ->setTag("min", new IntTag($this->min))
                ->setTag("max", new IntTag($this->max))
            );
    }
}
