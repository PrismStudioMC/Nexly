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
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FuelItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected float $duration
	) {
		if ($this->duration < 0.05) {
			throw new \InvalidArgumentException("Duration must be at least 0.05");
		}

		if ($this->duration > 107374180) {
			throw new \InvalidArgumentException("Duration must be at most 107374180");
		}
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::FUEL->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("duration", new FloatTag($this->duration));
	}
}
