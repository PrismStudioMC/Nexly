<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\block\Block;

class StickyType
{
    public const NONE = 'none';
    public const SAME = 'same';

    /**
     * @param Block $block
     * @return string|null
     */
    public static function from(Block $block): ?string
    {
        return null;
    }
}
