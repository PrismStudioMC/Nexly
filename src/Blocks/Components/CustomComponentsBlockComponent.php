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
class CustomComponentsBlockComponent extends BlockComponent
{
	public function __construct(
		protected bool $hasPlayerInteract = true,
		protected bool $hasPlayerPlacing = true,
		protected bool $isV1Component = true,
	) {
	}

	/**
	 * Returns the name of the component.
	 */
	public function getName() : string
	{
		return BlockComponentIds::CUSTOM_COMPONENTS;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("hasPlayerInteract", new ByteTag($this->hasPlayerInteract))
			->setTag("hasPlayerPlacing", new ByteTag($this->hasPlayerPlacing))
			->setTag("isV1Component", new ByteTag($this->isV1Component));
	}
}
