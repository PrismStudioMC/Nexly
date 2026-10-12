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

namespace Nexly\Blocks\Components\Types;

use Nexly\Helpers\ComponentHelper;
use pocketmine\block\Beacon;
use pocketmine\block\Bedrock;
use pocketmine\block\Block;
use pocketmine\block\ChorusFlower;
use pocketmine\block\ChorusPlant;
use pocketmine\block\Cobweb;
use pocketmine\block\EnchantingTable;
use pocketmine\block\EnderChest;
use pocketmine\block\EndPortalFrame;
use pocketmine\block\Flowable;
use pocketmine\block\GlazedTerracotta;
use pocketmine\block\Jukebox;
use pocketmine\block\Leaves;
use pocketmine\block\Melon;
use pocketmine\block\MonsterSpawner;
use pocketmine\block\Pumpkin;

final class MovementType
{
	public const PUSH_PULL = 'push_pull';
	public const PUSH = 'push';
	public const POPPED = 'popped';
	public const IMMOVABLE = 'immovable';

	public static function from(Block $block) : ?string
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
