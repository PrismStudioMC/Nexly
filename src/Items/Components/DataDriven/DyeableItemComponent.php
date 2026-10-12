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
use pocketmine\nbt\tag\StringTag;
use function preg_match;

#[Attribute(Attribute::TARGET_CLASS)]
class DyeableItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected string $color = "#ffffff",
	) {
		if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
			throw new \InvalidArgumentException("Invalid color format. Use hex format like #RRGGBB or #RGB.");
		}
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::DYEABLE->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("default_color", new StringTag($this->color));
	}
}
