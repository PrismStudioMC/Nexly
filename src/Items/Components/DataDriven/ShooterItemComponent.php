<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemAmmunition;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ShooterItemComponent extends DataDrivenItemComponent
{
    /**
     * @param array $ammunitions
     * @param bool $chargeOnDraw
     * @param float $maxDrawDuration
     * @param bool $scalePowerByDrawDuration
     */
    public function __construct(
        private readonly array $ammunitions = [],
        private readonly bool  $chargeOnDraw = false,
        private readonly float $maxDrawDuration = 0.0,
        private readonly bool  $scalePowerByDrawDuration = true
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::SHOOTER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("charge_on_draw", new ByteTag($this->chargeOnDraw))
            ->setTag("max_draw_duration", new FloatTag($this->maxDrawDuration))
            ->setTag("scale_power_by_draw_duration", new ByteTag($this->scalePowerByDrawDuration))
            ->setTag("ammunition", new ListTag(array_map(fn (ItemAmmunition $ammunition) => $ammunition->toNBT(), $this->ammunitions), NBT::TAG_Compound));
    }
}
