<?php

namespace YOUR_NAMESPACE;

use Nexly\Events\Impl\BlockRegistryEvent;
use Nexly\Events\Impl\ItemRegistryEvent;
use Nexly\Events\NexlyEventManager;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\BlockTypeInfo;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\ToolTier;
use pocketmine\plugin\PluginBase;

class MainExample extends PluginBase
{
    protected function onLoad(): void
    {
        NexlyEventManager::getInstance()->listen(ItemRegistryEvent::class, static function (ItemRegistryEvent $ev): void {
            $ev->register("nexly:ruby", new Item(new ItemIdentifier(ItemTypeIds::newId()), "Ruby"));
        });

        NexlyEventManager::getInstance()->listen(BlockRegistryEvent::class, static function (BlockRegistryEvent $ev): void {
            $ev->register("nexly:ruby_block", static fn(int $id) => new Block(new BlockIdentifier($id), "Ruby Block", new BlockTypeInfo(BlockBreakInfo::pickaxe(5.0, ToolTier::IRON, 30.0))));
        });
    }
}