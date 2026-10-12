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
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CameraComponent extends LegacyItemComponent
{
	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return LegacyComponentIds::CAMERA->getValue();
	}

	public function __construct(
		protected float $blackBarsDuration,
		protected float $blackBarsScreenRatio,
		protected float $shutterDuration,
		protected float $shutterScreenRatio,
		protected float $pictureDuration,
		protected float $slideAwayDuration,
	) {
	}

	/**
	 * Get the maximum damage value.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("black_bars_duration", new FloatTag($this->blackBarsDuration))
			->setTag("black_bars_screen_ratio", new FloatTag($this->blackBarsScreenRatio))
			->setTag("shutter_duration", new FloatTag($this->shutterDuration))
			->setTag("shutter_screen_ratio", new FloatTag($this->shutterScreenRatio))
			->setTag("picture_duration", new FloatTag($this->pictureDuration))
			->setTag("slide_away_duration", new FloatTag($this->slideAwayDuration));
	}
}
