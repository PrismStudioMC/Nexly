<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\LiquidRule;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LiquidDetectionComponent extends BlockComponent
{
    /**
     * @param LiquidRule[] $rules
     */
    public function __construct(
        private array $rules,
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::LIQUID_DETECTION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("detectionRules", new ListTag(array_map(fn(LiquidRule $rule) => $rule->toNBT(), $this->rules), NBT::TAG_Compound));
    }
}
