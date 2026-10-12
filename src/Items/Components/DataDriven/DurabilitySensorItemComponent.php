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
use Nexly\Items\Components\DataDriven\Types\DurabilityThreshold;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class DurabilitySensorItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected array $thresholds,
	) {
		if (empty($this->thresholds)) {
			throw new \InvalidArgumentException("Thresholds array cannot be empty.");
		}
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::DURABILITY_SENSOR->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("durability_thresholds", new ListTag(array_map(fn (DurabilityThreshold $threshold) => $threshold->toNBT(), $this->thresholds), NBT::TAG_Compound));
	}
}
