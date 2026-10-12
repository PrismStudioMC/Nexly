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
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class TickBlockComponent extends BlockComponent
{
	public function __construct(
		protected int $min = 20,
		protected int $max = 60,
		protected bool $looping = true
	) {
	}

	public function getName() : string
	{
		return BlockComponentIds::TICK;
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag('interval_range', new ListTag([
				new IntTag($this->min),
				new IntTag($this->max)
			], NBT::TAG_Int))
			->setByte('looping', (int) $this->looping);
	}
}
