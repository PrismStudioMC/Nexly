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
use Nexly\Blocks\Components\Types\MovementType;
use Nexly\Blocks\Components\Types\StickyType;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MovableBlockComponent extends BlockComponent
{
	public function __construct(
		protected string $movementType = MovementType::PUSH_PULL,
		protected ?string $sticky = null
	) {
		if ($sticky === StickyType::SAME && $movementType !== MovementType::PUSH_PULL) {
			throw new InvalidArgumentException('Sticky type SAME only works with push_pull movement.');
		}
	}

	public function getName() : string
	{
		return BlockComponentIds::MOVABLE;
	}

	public function toNBT() : CompoundTag
	{
		$tag = CompoundTag::create()->setString('movement_type', $this->movementType);
		if ($this->sticky !== null) {
			$tag->setString('sticky', $this->sticky);
		}
		return $tag;
	}
}
