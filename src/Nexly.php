<?php

namespace Nexly;

use Nexly\Blocks\AsyncInitialization;
use Nexly\Blocks\BlockPalette;
use Nexly\Events\Impl\BlockRegistryEvent;
use Nexly\Events\Impl\ItemRegistryEvent;
use Nexly\Events\Impl\RecipeRegistryEvent;
use Nexly\Listener\BlockBreakingListener;
use Nexly\Mappings\BlockMappings;
use Nexly\Recipes\NexlyRecipes;
use Nexly\Tasks\AsyncRegisterBlocksTask;
use pocketmine\event\EventPriority;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Nexly extends PluginBase
{
    protected function onLoad(): void
    {
        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
    }

    protected function onEnable(): void
    {
        $config = $this->getConfig();
        if ($config->get("enable-block-breaking", true)) {
            try {
                $this->getServer()->getpluginManager()->registerEvents(new BlockBreakingListener(), $this);
            } catch (\Exception $e) {
                $this->getLogger()->error("Failed to register BlockBreakingListener: " . $e->getMessage());
            }
        } else {
            $this->getLogger()->notice("================================");
            $this->getLogger()->notice("You can enable the BlockBreakingListener in config.yml");
            $this->getLogger()->notice("This allows you to break blocks with custom items like the Emerald Pickaxe");
            $this->getLogger()->notice("================================");
        }

        $ev = new BlockRegistryEvent();
        $ev->trigger();
        $this->getLogger()->notice("Registered " . $ev->getCount() . " blocks.");

        $ev = new ItemRegistryEvent();
        $ev->trigger();
        $this->getLogger()->notice("Registered " . $ev->getCount() . " items.");

        $this->getServer()->getPluginManager()->registerEvent(DataPacketSendEvent::class, function (DataPacketSendEvent $ev): void {
            $packets = $ev->getPackets();
            foreach ($packets as $packet) {
                if ($packet instanceof StartGamePacket) {
                    $packet->blockPalette = BlockMappings::getInstance()->getEntries();
                }
            }
        }, EventPriority::NORMAL, $this);

        $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function (): void {
            $pool = $this->getServer()->getAsyncPool();
            $blocks = AsyncInitialization::getBlocks();

            $ev = new RecipeRegistryEvent();
            $ev->trigger();
            $this->getLogger()->notice("Registered " . $ev->getShaped() . " shaped and " . $ev->getShapeless() . " shapeless recipes.");

            NexlyRecipes::getInstance()->registers();
            BlockPalette::getInstance()->apply();

            $this->getLogger()->notice("Sending " . count($blocks) . " blocks to worker threads for registration...");
            $pool->addWorkerStartHook(function (int $worker) use ($pool, $blocks): void {
                $pool->submitTaskToWorker(new AsyncRegisterBlocksTask($blocks), $worker);
            });
        }), 1);
    }
}
