<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DamageAbsorptionItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string[] $causes
     */
    public function __construct(
        protected array $causes,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DAMAGE_ABSORPTION->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("absorbable_causes", new ListTag($this->causes, NBT::TAG_String));
    }
}
