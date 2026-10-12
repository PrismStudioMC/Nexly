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
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ThrowableItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected bool  $doSwingAnimation = true,
		protected float $launchPowerScale = 1.0,
		protected float $maxDrawDuration = 0.0,
		protected float $maxLaunchPower = 1.0,
		protected float $minDrawDuration = 0.0,
		protected bool  $scalePowerByDrawDuration = false
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::THROWABLE->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("do_swing_animation", new ByteTag($this->doSwingAnimation))
			->setTag("launch_power_scale", new FloatTag($this->launchPowerScale))
			->setTag("max_draw_duration", new FloatTag($this->maxDrawDuration))
			->setTag("max_launch_power", new FloatTag($this->maxLaunchPower))
			->setTag("min_draw_duration", new FloatTag($this->minDrawDuration))
			->setTag("scale_power_by_draw_duration", new ByteTag($this->scalePowerByDrawDuration));
	}
}
