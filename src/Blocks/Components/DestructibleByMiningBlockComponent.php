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
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructibleByMiningBlockComponent extends BlockComponent
{
	public function __construct(
		protected float $secondsToDestroy = 0.0,
		protected array $itemSpecificSpeeds = [],
	) {
	}

	/**
	 * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
	 */
	public function getName() : string
	{
		return BlockComponentIds::DESTRUCTIBLE_BY_MINING;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		$items = array_map(fn (ItemSpecificSpeed $speed) => $speed->toNBT(), $this->itemSpecificSpeeds);
		return CompoundTag::create()
			->setTag("seconds_to_destroy", new FloatTag($this->secondsToDestroy))
			->setTag("item_specific_speeds", new ListTag($items, NBT::TAG_Compound));
	}
}
