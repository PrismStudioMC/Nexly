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
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class IconProperty extends PropertyItemComponent
{
	public function __construct(
		protected string $icon,
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return PropertyComponentIds::ICON->getValue();
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("texture", new StringTag($this->icon))
			->setTag(
				"textures",
				CompoundTag::create()
				->setTag("default", new StringTag($this->icon))
				->setTag("dyed", new StringTag($this->icon . ".dyed"))
				->setTag("icon_trim", new StringTag($this->icon . ".trim"))
			);
	}
}
