<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\block\BaseSign;
use pocketmine\block\Block;
use pocketmine\block\Campfire;
use pocketmine\block\Fence;
use pocketmine\block\Flowable;
use pocketmine\block\Stair;
use pocketmine\block\Wall;

class SupportShape
{
    public const FENCE = "fence";
    public const STAIR = "stair";
    public const NONE = "none";

    /**
     * @param Block $block
     * @return string|null
     */
    public static function from(Block $block): ?string
    {
        return match(true) {
            $block instanceof Fence, $block instanceof Wall => SupportShape::FENCE,
            $block instanceof Stair => SupportShape::STAIR,

            $block instanceof Flowable, $block instanceof BaseSign, $block instanceof Campfire, !$block->isSolid() => SupportShape::NONE,
            default => null
        };
    }
}
