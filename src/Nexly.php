<?php

/*
 *  _   _           _
 * | \ | | _____  _| |_   _
 * |  \| |/ _ \ \/ / | | | |
 * | |\  |  __/>  <| | |_| |
 * |_| \_|\___/_/\_\_|\__, |
 *                    |___/
 *
 * Nexly - A modern PocketMine-MP framework for custom blocks and items.
 *
 * This project is free software: you can redistribute it and/or modify
 * it under the terms of the Apache License, Version 2.0 as published by
 * the Apache Software Foundation.
 *
 * @author Zwuiix-cmd
 * @link   https://github.com/Zwuiix-cmd/Nexly
 *
 *
 */

namespace Nexly;

use muqsit\simplepackethandler\SimplePacketHandler;
use Nexly\Blocks\AsyncInitialization;
use Nexly\Blocks\BlockPalette;
use Nexly\Events\Impl\BlockRegistryEvent;
use Nexly\Events\Impl\ItemRegistryEvent;
use Nexly\Listeners\BlockBreakingListener;
use Nexly\Listeners\LadderClimbingListener;
use Nexly\Mappings\BlockMappings;
use Nexly\Recipes\NexlyRecipes;
use Nexly\Tasks\AsyncRegisterBlocksTask;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use zwuiix\extpack\ExtPack;
use function array_merge;
use function count;

class Nexly extends PluginBase
{
	public const PACK_URL = "https://github.com/Zwuiix-cmd/Nexly-Assets/archive/refs/tags/v";

	protected function onLoad() : void
	{
		$this->saveDefaultConfig();
		$this->saveResource("config.yml");
	}

	protected function onEnable() : void
	{
		ExtPack::fromUrl(self::PACK_URL . $this->getDescription()->getVersion() . ".zip");

		$config = $this->getConfig();
		if ($config->get("enable-block-breaking", true)) {
			try {
				$this->getServer()->getPluginManager()->registerEvents(new BlockBreakingListener(), $this);
			} catch (\Exception $e) {
				$this->getLogger()->error("Failed to register BlockBreakingListener: " . $e->getMessage());
			}
		}

		if ($config->get("enable-ladder-climbing", true)) {
			try {
				$this->getServer()->getPluginManager()->registerEvents(new LadderClimbingListener(), $this);
			} catch (\Exception $e) {
				$this->getLogger()->error("Failed to register LadderClimbingListener: " . $e->getMessage());
			}
		}

		$ev = new BlockRegistryEvent();
		$ev->trigger();
		$this->getLogger()->notice("Registered " . $ev->getCount() . " blocks.");

		$ev = new ItemRegistryEvent();
		$ev->trigger();
		$this->getLogger()->notice("Registered " . $ev->getCount() . " items.");

		$monitor = SimplePacketHandler::createMonitor($this);
		$monitor->monitorOutgoing(function (StartGamePacket $pk, NetworkSession $target) : void {
			$pk->blockNetworkIdsAreHashes = true; // Always true for Nexly
			$pk->blockPalette = array_merge($pk->blockPalette, BlockMappings::getInstance()->getEntries());
		});

		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(function () : void {
			$pool = $this->getServer()->getAsyncPool();
			$blocks = AsyncInitialization::getBlocks();

			NexlyRecipes::getInstance()->registers($this);
			BlockPalette::getInstance()->apply();

			$this->getLogger()->notice("Sending " . count($blocks) . " blocks to worker threads for registration...");
			$pool->addWorkerStartHook(function (int $worker) use ($pool, $blocks) : void {
				$pool->submitTaskToWorker(new AsyncRegisterBlocksTask($blocks), $worker);
			});
		}), 1);
	}
}
