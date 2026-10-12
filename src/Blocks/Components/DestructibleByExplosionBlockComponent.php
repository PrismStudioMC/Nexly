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
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructibleByExplosionBlockComponent extends BlockComponent
{
	public function __construct(
		protected float $resistance = 0.0,
	) {
		if ($resistance < 0.0) {
			throw new \InvalidArgumentException("Explosion resistance cannot be negative.");
		}
	}

	/**
	 * Determines whether the block is destructible by explosions. The default is `0.0` if this component is omitted
	 */
	public function getName() : string
	{
		return BlockComponentIds::DESTRUCTIBLE_BY_EXPLOSION;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("explosion_resistance", new FloatTag($this->resistance));
	}
}
