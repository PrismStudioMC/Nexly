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

use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

class ItemMiningSpeed
{
	public function __construct(
		protected string $item,
		protected float $destroySpeed,
	) {
		if ($item === "") {
			throw new InvalidArgumentException("Item identifier cannot be empty.");
		}

		if ($destroySpeed < 0.0) {
			throw new InvalidArgumentException("Item-specific destroy speed cannot be negative.");
		}
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("item", new StringTag($this->item))
			->setTag("destroy_speed", new FloatTag($this->destroySpeed));
	}
}
