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
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LeashableBlockComponent extends BlockComponent
{
	public function __construct(
		protected ?Vector3 $offset = null
	) {
	}

	public function getName() : string
	{
		return BlockComponentIds::LEASHABLE;
	}

	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create();
		if ($this->offset !== null) {
			$nbt->setTag('offset', new ListTag([
				new FloatTag($this->offset->x),
				new FloatTag($this->offset->y),
				new FloatTag($this->offset->z),
			], NBT::TAG_Float));
		}
		return $nbt;
	}

}
