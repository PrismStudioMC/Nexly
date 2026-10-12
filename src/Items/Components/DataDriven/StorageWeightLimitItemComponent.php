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

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageWeightLimitItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected int $capacity,
	) {
		if ($capacity < 0) {
			throw new \InvalidArgumentException("Capacity must be a non-negative integer.");
		}

		if ($capacity > 64) {
			throw new \InvalidArgumentException("Capacity cannot exceed 64.");
		}
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::STORAGE_WEIGHT_LIMIT->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("max_weight_limit", new IntTag($this->capacity));
	}
}
