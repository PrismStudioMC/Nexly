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
use Nexly\Items\Components\DataDriven\Types\ItemAnimation;
use pocketmine\entity\Consumable;
use pocketmine\item\Item;
use pocketmine\item\Potion;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class UseAnimationProperty extends PropertyItemComponent
{
	public function __construct(
		protected ItemAnimation $animation
	) {
	}

	/**
	 * Create an UseAnimationProperty from an Item.
	 */
	public static function fromItem(Item $item) : UseAnimationProperty
	{
		return new self(match (true) {
			$item instanceof Consumable => ItemAnimation::EAT,
			$item instanceof Potion => ItemAnimation::DRINK,
			default => ItemAnimation::NONE,
		});
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return PropertyComponentIds::USE_ANIMATION->getValue();
	}

	public function toNBT() : IntTag
	{
		return new IntTag($this->animation->getValue());
	}
}
