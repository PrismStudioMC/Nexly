<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\DamageCause;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DamageAbsorptionItemComponent extends DataDrivenItemComponent
{
    /**
     * @param DamageCause[] $causes
     */
    public function __construct(
        private readonly array $causes,
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
            ->setTag("absorbable_causes", new ListTag(array_map(fn (DamageCause $cause) => $cause->getValue(), $this->causes), NBT::TAG_String));
    }
}
