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

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_piercing_weapon?view=minecraft-bedrock-stable
 * @since 1.21.120
 * @internal
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PiercingWeaponItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected float $hitboxMargin = 0,
		protected int   $min = 0,
		protected int   $max = 3,
	) {
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::PIERCING_WEAPON->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("hitbox_margin", new IntTag($this->hitboxMargin))
			->setTag(
				"reach",
				CompoundTag::create()
				->setTag("min", new IntTag($this->min))
				->setTag("max", new IntTag($this->max))
			);
	}
}
