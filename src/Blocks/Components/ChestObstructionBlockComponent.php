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
use Nexly\Blocks\Components\Types\ObstructionRule;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ChestObstructionBlockComponent extends BlockComponent
{
	public function __construct(
		private readonly string $rule = ObstructionRule::SHAPE,
	) {
	}

	/**
	 * The name of the component.
	 */
	public function getName() : string
	{
		return BlockComponentIds::CHEST_OBSTRUCTION;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("obstruction_rule", new StringTag($this->rule));
	}
}
