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

	public static function from(Block $block) : ?string
	{
		return match(true) {
			$block instanceof Fence, $block instanceof Wall => SupportShape::FENCE,
			$block instanceof Stair => SupportShape::STAIR,

			$block instanceof Flowable, $block instanceof BaseSign, $block instanceof Campfire, !$block->isSolid() => SupportShape::NONE,
			default => null
		};
	}
}
