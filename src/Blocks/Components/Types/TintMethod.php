<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\block\Block;
use pocketmine\block\Grass;
use pocketmine\block\Leaves;
use pocketmine\block\utils\LeavesType;
use pocketmine\block\Water;

class TintMethod
{
    public const NONE = "none";
    public const DEFAULT_FOLIAGE = "default_foliage";
    public const BIRCH_FOLIAGE = "birch_foliage";
    public const EVERGREEN_FOLIAGE = "evergreen_foliage";
    public const DRY_FOLIAGE = "dry_foliage";
    public const GRASS = "grass";
    public const WATER = "water";

    /**
     * @param Block $block
     * @return string|null
     */
    public static function from(Block $block): ?string
    {
        return match(true) {
            $block instanceof Grass => TintMethod::GRASS,
            $block instanceof Water => TintMethod::WATER,
            $block instanceof Leaves => match($block->getLeavesType()) {
                LeavesType::SPRUCE => TintMethod::EVERGREEN_FOLIAGE,
                LeavesType::BIRCH => TintMethod::BIRCH_FOLIAGE,
                LeavesType::OAK,
                LeavesType::JUNGLE,
                LeavesType::ACACIA,
                LeavesType::DARK_OAK => TintMethod::DEFAULT_FOLIAGE,
                default => null
            },
            default => null
        };
    }
}
