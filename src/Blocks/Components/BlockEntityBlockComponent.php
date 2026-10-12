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
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class BlockEntityBlockComponent extends BlockComponent
{
	public function __construct(
		private readonly bool $dynamicProperties = false,
		private readonly ?int $containerSlotCount = null,
	) {
		if ($containerSlotCount !== null && ($containerSlotCount < 1 || $containerSlotCount > 54)) {
			throw new InvalidArgumentException("Container slot count must be between 1 and 54.");
		}
	}

	/**
	 * The name of the component.
	 */
	public function getName() : string
	{
		return BlockComponentIds::BLOCK_ENTITY;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create()
			->setTag("dynamic_properties", new ByteTag($this->dynamicProperties));

		if ($this->containerSlotCount !== null) {
			$nbt->setTag("container", CompoundTag::create()
				->setTag("slot_count", new IntTag($this->containerSlotCount)));
		}

		return $nbt;
	}
}
