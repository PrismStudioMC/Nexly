<?php

namespace Nexly\Blocks\Components\Types;

use Nexly\Helpers\ComponentHelper;
use pocketmine\block\Beacon;
use pocketmine\block\Bedrock;
use pocketmine\block\Block;
use pocketmine\block\ChorusFlower;
use pocketmine\block\ChorusPlant;
use pocketmine\block\Cobweb;
use pocketmine\block\CryingObsidian;
use pocketmine\block\EnchantingTable;
use pocketmine\block\EnderChest;
use pocketmine\block\EndPortalFrame;
use pocketmine\block\Flowable;
use pocketmine\block\GlazedTerracotta;
use pocketmine\block\Jukebox;
use pocketmine\block\Leaves;
use pocketmine\block\Melon;
use pocketmine\block\MonsterSpawner;
use pocketmine\block\Obsidian;
use pocketmine\block\Pumpkin;
use pocketmine\block\ReinforcedDeepslate;

final class MovementType
{
    public const PUSH_PULL = 'push_pull';
    public const PUSH = 'push';
    public const POPPED = 'popped';
    public const IMMOVABLE = 'immovable';

    /**
     * @param Block $block
     * @return string|null
     */
    public static function from(Block $block): ?string
    {
        return match(true) {
            $block instanceof Bedrock,
            $block instanceof MonsterSpawner,
            $block instanceof EnchantingTable,
            $block instanceof EnderChest,
            $block instanceof EndPortalFrame,
            $block instanceof Jukebox,
            $block instanceof Beacon,
            ComponentHelper::hasTile($block) => self::IMMOVABLE,

            $block instanceof Flowable,
            $block instanceof Leaves,
            $block instanceof Melon,
            $block instanceof Pumpkin,
            $block instanceof Cobweb,
            $block instanceof ChorusPlant,
            $block instanceof ChorusFlower,
            !$block->isSolid() => self::POPPED,

            $block instanceof GlazedTerracotta => self::PUSH,
            default => self::PUSH_PULL
        };
    }
}
