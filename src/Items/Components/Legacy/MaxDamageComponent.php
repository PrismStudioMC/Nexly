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
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaxDamageComponent extends LegacyItemComponent
{
	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return LegacyComponentIds::MAX_DAMAGE->getValue();
	}

	public function __construct(
		protected int $maxDamage
	) {
	}

	/**
	 * Get the maximum damage value.
	 */
	public function toNBT() : IntTag
	{
		return new IntTag($this->maxDamage);
	}
}
