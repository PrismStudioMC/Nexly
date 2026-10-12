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
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class EntityFallOnBlockComponent extends BlockComponent
{
	public function __construct(
		protected float $minFallDistance = 0.0
	) {
		if ($minFallDistance < 0.0) {
			throw new InvalidArgumentException('Minimum fall distance cannot be negative.');
		}
	}

	public function getName() : string
	{
		return BlockComponentIds::ENTITY_FALL_ON;
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag('min_fall_distance', new FloatTag($this->minFallDistance));
	}
}
