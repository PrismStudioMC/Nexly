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
use Nexly\Blocks\Components\Types\SupportShape;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use function strtolower;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/blockreference/examples/blockcomponents/minecraftblock_support?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SupportBlockComponent extends BlockComponent
{
	public function __construct(
		protected string $shape = SupportShape::NONE,
	) {
	}

	public function getName() : string
	{
		return BlockComponentIds::SUPPORT;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("shape", new StringTag(strtolower($this->shape)));
	}
}
