<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class LiquidRule
{
    /**
     * @param bool $canContainLiquid
     * @param string $liquidType
     * @param string $onLiquidTouches
     * @param array<string> $stopsLiquidFlowingFromDirection
     * @param bool $useLiquidClipping
     */
    public function __construct(
        protected bool              $canContainLiquid,
        protected string            $liquidType = LiquidType::WATER,
        protected string            $onLiquidTouches = LiquidTouchAction::BLOCKING,
        protected array             $stopsLiquidFlowingFromDirection = [],
        protected bool              $useLiquidClipping = false,
    )
    {
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $liquidsDirection = array_map(
            fn (string $direction) => new StringTag($direction),
            $this->stopsLiquidFlowingFromDirection
        );
        return CompoundTag::create()
            ->setTag("canContainLiquid", new ByteTag($this->canContainLiquid))
            ->setTag("liquidType", new StringTag($this->liquidType))
            ->setTag("onLiquidTouches", new StringTag($this->onLiquidTouches))
            ->setTag("stopsLiquidFromDirection", new ListTag(array_values($liquidsDirection), NBT::TAG_String))
            ->setTag("useLiquidClipping", new ByteTag($this->useLiquidClipping));
    }
}
