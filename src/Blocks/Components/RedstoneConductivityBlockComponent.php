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
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RedstoneConductivityBlockComponent extends BlockComponent
{
	public function __construct(
		protected bool $allowsWireToStepDown = true,
		protected bool $redstoneConductor = false
	) {
	}

	public function getName() : string
	{
		return BlockComponentIds::REDSTONE_CONDUCTIVITY;
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag('allows_wire_to_step_down', new ByteTag($this->allowsWireToStepDown))
			->setTag('redstone_conductor', new ByteTag($this->redstoneConductor));
	}
}
