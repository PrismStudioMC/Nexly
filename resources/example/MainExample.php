<?php

namespace Nexly\Exemple;

use Nexly\Blocks\Vanilla\NexlyFence;
use Nexly\Events\Impl\BlockRegistryEvent;
use Nexly\Events\Impl\ItemRegistryEvent;
use Nexly\Events\NexlyEventManager;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\FenceGate;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\utils\WoodType;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\ToolTier;
use pocketmine\plugin\PluginBase;

class MainExample extends PluginBase
{
    /**
     * @return void
     */
    protected function onLoad(): void
    {
        NexlyEventManager::getInstance()->listen(ItemRegistryEvent::class, static function (ItemRegistryEvent $ev): void {
            $ev->register("nexly:ruby", new Item(new ItemIdentifier(ItemTypeIds::newId()), "Ruby"));
        });

        NexlyEventManager::getInstance()->listen(BlockRegistryEvent::class, static function (BlockRegistryEvent $ev): void {
            $breakInfo = static fn() => new BlockTypeInfo(BlockBreakInfo::pickaxe(5.0, ToolTier::IRON, 30.0));

            $ev->register("nexly:ruby_carrot", static fn(int $id) => new RubyCarrot(new BlockIdentifier($id), "Ruby Carrot", $breakInfo()));
            $ev->register("nexly:ruby_block", static fn(int $id) => new Block(new BlockIdentifier($id), "Ruby Block", $breakInfo()));
            $ev->register("nexly:ruby_slab", static fn(int $id) => new Slab(new BlockIdentifier($id), "Ruby Slab", $breakInfo()));
            $ev->register("nexly:ruby_stair", static fn(int $id) => new Stair(new BlockIdentifier($id), "Ruby Stair", $breakInfo()));
            $ev->register("nexly:ruby_fence", static fn(int $id) => new NexlyFence(new BlockIdentifier($id), "Ruby Fence", $breakInfo()));
            $ev->register("nexly:ruby_fence_gate", static fn(int $id) => new FenceGate(new BlockIdentifier($id), "Ruby Fence", $breakInfo(), WoodType::OAK));
        });
    }
}