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
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\StringTag;
use function is_string;

#[Attribute(Attribute::TARGET_CLASS)]
class InteractButtonProperty extends PropertyItemComponent
{
	public function __construct(
		protected bool|string $value
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return PropertyComponentIds::INTERACT_BUTTON->getValue();
	}

	public function toNBT() : StringTag|ByteTag
	{
		return is_string($this->value) ? new StringTag($this->value) : new ByteTag($this->value);
	}
}
