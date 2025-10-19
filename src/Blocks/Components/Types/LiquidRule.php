<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

readonly class LiquidRule
{
    public function __construct(
        private bool              $canContainLiquid,
        private LiquidType        $liquidType = LiquidType::WATER,
        private LiquidTouchAction $onLiquidTouches = LiquidTouchAction::NO_REACTION,
        private bool              $stopsLiquidFromDirection = false,
    )
    {
    }

    /**
     * @return bool
     */
    public function canContainLiquid(): bool
    {
        return $this->canContainLiquid;
    }

    /**
     * @return LiquidType
     */
    public function getLiquidType(): LiquidType
    {
        return $this->liquidType;
    }

    /**
     * @return LiquidTouchAction
     */
    public function onLiquidTouches(): LiquidTouchAction
    {
        return $this->onLiquidTouches;
    }

    /**
     * @return bool
     */
    public function stopsLiquidFromDirection(): bool
    {
        return $this->stopsLiquidFromDirection;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("canContainLiquid", new ByteTag($this->canContainLiquid))
            ->setTag("liquidType", new StringTag($this->liquidType->getValue()))
            ->setTag("onLiquidTouches", new StringTag($this->onLiquidTouches->getValue()))
            ->setTag("stopsLiquidFromDirection", new ByteTag($this->stopsLiquidFromDirection));
    }
}