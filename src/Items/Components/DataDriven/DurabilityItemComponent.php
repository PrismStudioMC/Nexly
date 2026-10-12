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
class DurabilityItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected int $durability,
		protected int $min = 0,
		protected int $max = 0,
	) {
		if ($durability < 1) {
			throw new \InvalidArgumentException("Durability must be at least 1");
		}

		if ($min < 0 || $max < 0) {
			throw new \InvalidArgumentException("Min and Max damage chance must be at least 0");
		}

		if ($min > $max) {
			throw new \InvalidArgumentException("Min damage chance cannot be greater than Max damage chance");
		}

		if ($max > 100) {
			throw new \InvalidArgumentException("Max damage chance cannot be greater than 100");
		}
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::DURABILITY->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("max_durability", new IntTag($this->durability))
			->setTag(
				"damage_chance",
				CompoundTag::create()
				->setTag("min", new IntTag($this->min))
				->setTag("max", new IntTag($this->max))
			);
	}
}
