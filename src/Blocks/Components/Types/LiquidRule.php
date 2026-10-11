<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

readonly class LiquidRule
{
    /**
     * @param bool $canContainLiquid
     * @param LiquidType $liquidType
     * @param LiquidTouchAction $onLiquidTouches
     * @param array<Face|string> $stopsLiquidFlowingFromDirection
     * @param bool $useLiquidClipping
     */
    public function __construct(
        protected bool              $canContainLiquid,
        protected LiquidType        $liquidType = LiquidType::WATER,
        protected LiquidTouchAction $onLiquidTouches = LiquidTouchAction::BLOCKING,
        protected array             $stopsLiquidFlowingFromDirection = [],
        protected bool              $useLiquidClipping = false,
    ) {
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
     * @return list<Face|string>
     */
    public function stopsLiquidFromDirection(): array
    {
        return $this->stopsLiquidFlowingFromDirection;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $liquidsDirection = array_map(
            fn (Face|string $direction) => new StringTag($direction instanceof Face ? $direction->value : $direction),
            $this->stopsLiquidFlowingFromDirection
        );
        return CompoundTag::create()
            ->setTag("canContainLiquid", new ByteTag($this->canContainLiquid))
            ->setTag("liquidType", new StringTag($this->liquidType->getValue()))
            ->setTag("onLiquidTouches", new StringTag($this->onLiquidTouches->getValue()))
            ->setTag("stopsLiquidFromDirection", new ListTag(array_values($liquidsDirection), NBT::TAG_String))
            ->setTag("useLiquidClipping", new ByteTag($this->useLiquidClipping));
    }
}
