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
use Nexly\Blocks\Components\Types\RangeOffset;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

#[Attribute(Attribute::TARGET_CLASS)]
class RandomOffsetBlockComponent extends BlockComponent
{
	public function __construct(
		protected BlockPosition $steps,
		protected RangeOffset   $offset,
	) {
		if ($steps->getX() < 0 || $steps->getY() < 0 || $steps->getZ() < 0) {
			throw new InvalidArgumentException("Random offset steps cannot be negative (zero means continuous).");
		}
	}

	/**
	 * Returns the name of the component.
	 */
	public function getName() : string
	{
		return BlockComponentIds::RANDOM_OFFSET;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag(
				"x",
				CompoundTag::create()
				->setTag("steps", new IntTag($this->steps->getX()))
				->setTag(
					"range",
					CompoundTag::create()
					->setTag("min", new FloatTag($this->offset->getMin()->getX()))
					->setTag("max", new FloatTag($this->offset->getMax()->getX()))
				)
			)
			->setTag(
				"y",
				CompoundTag::create()
				->setTag("steps", new IntTag($this->steps->getY()))
				->setTag(
					"range",
					CompoundTag::create()
					->setTag("min", new FloatTag($this->offset->getMin()->getY()))
					->setTag("max", new FloatTag($this->offset->getMax()->getY()))
				)
			)
			->setTag(
				"z",
				CompoundTag::create()
				->setTag("steps", new IntTag($this->steps->getZ()))
				->setTag(
					"range",
					CompoundTag::create()
					->setTag("min", new FloatTag($this->offset->getMin()->getZ()))
					->setTag("max", new FloatTag($this->offset->getMax()->getZ()))
				)
			);
	}
}
