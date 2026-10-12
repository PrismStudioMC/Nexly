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

	public static function from(Block $block) : ?string
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
