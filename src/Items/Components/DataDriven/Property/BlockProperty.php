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

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockProperty extends PropertyItemComponent
{
	public function __construct(
		protected string $blockName
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return PropertyComponentIds::BLOCK->getValue();
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

	public function toNBT() : StringTag
	{
		return new StringTag($this->blockName);
	}
}
