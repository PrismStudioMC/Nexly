<?php

namespace Nexly\Blocks;

use Closure;
use Nexly\Items\Creative\CreativeInfo;

class NexlyBlocks
{
    /**
     * Registers a new block with the given string ID, block creation closure, and optional creative info.
     *
     * @param string $stringId
     * @param Closure $block
     * @param CreativeInfo|null $creativeInfo
     * @param bool $addToCreative
     * @return BlockBuilder
     */
    public static function register(string $stringId, Closure $block, ?CreativeInfo $creativeInfo = null, bool $addToCreative = true): BlockBuilder
    {
        return self::buildComplex($stringId, $block, $creativeInfo)->register($addToCreative);
    }

    /**
     * Builds a complex block with the given string ID, block creation closure, and optional creative info.
     *
     * @param string $stringId
     * @param Closure $block
     * @param CreativeInfo|null $creativeInfo
     * @return BlockBuilder
     */
    public static function buildComplex(string $stringId, Closure $block, ?CreativeInfo $creativeInfo = null): BlockBuilder
    {
        return BlockBuilder::create()
            ->setStringId($stringId)
            ->setBlock($block)
            ->setCreativeInfo($creativeInfo);
    }
}
