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

namespace Nexly\Items\Components\Legacy;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockRenderComponent extends LegacyItemComponent
{
	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return LegacyComponentIds::BLOCK_RENDER->getValue();
	}

	/**
	 * Create a BlockComponent from a Block instance.
	 */
	public static function from(Block $block) : self
	{
		return new self(
			GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
		);
	}

	public function __construct(
		protected string $name,
	) {
	}

	/**
	 * Get the maximum damage value.
	 */
	public function toNBT() : StringTag
	{
		return new StringTag($this->name);
	}
}
