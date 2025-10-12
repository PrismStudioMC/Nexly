<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\LiquidTouchAction;
use Nexly\Blocks\Components\Types\LiquidType;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LiquidDetectionComponent extends BlockComponent
{
    public function __construct(
        private readonly bool              $waterloggable,
        private readonly LiquidType        $type = LiquidType::WATER,
        private readonly LiquidTouchAction $toucheAction = LiquidTouchAction::NO_REACTION,
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public static function getName(): string
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
            ->setTag("detection_rules", CompoundTag::create()
                ->setTag("can_contain_liquid", new ByteTag($this->waterloggable))
                ->setTag("liquid_type", new StringTag($this->type->value))
                ->setTag("on_liquid_touches", new StringTag($this->toucheAction->value)));
    }
}
