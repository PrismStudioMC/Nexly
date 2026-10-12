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
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DamageProperty extends PropertyItemComponent
{
	public function __construct(
		protected int $damage
	) {
		if ($this->damage < -32768 || $this->damage > 32767) {
			throw new \InvalidArgumentException("Damage must be between -32768 and 32767");
		}
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return PropertyComponentIds::DAMAGE->getValue();
	}

	public function toNBT() : IntTag
	{
		return new IntTag($this->damage);
	}
}
