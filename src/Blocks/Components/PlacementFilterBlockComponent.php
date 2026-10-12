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

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\PlacementCondition;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class PlacementFilterBlockComponent extends BlockComponent
{
	public function __construct(
		protected array $conditions,
	) {
	}

	public function getName() : string
	{
		return BlockComponentIds::PLACEMENT_FILTER;
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag('conditions', new ListTag(array_map(fn (PlacementCondition $condition) : CompoundTag => $condition->toNBT(), $this->conditions)));
	}
}
