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
use Nexly\Items\Components\DataDriven\Types\OreTextureType;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @deprecated
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ArmorItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected OreTextureType $textureType,
		protected ?int           $protection = null,
	) {
	}

	/**
	 * Create an ArmorItemComponent from an Armor instance.
	 *
	 * @param Armor $item
	 */
	public static function from(Item $item) : self
	{
		return new self(
			OreTextureType::fromItem($item),
			$item instanceof Armor ? $item->getDefensePoints() : null
		);
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::ARMOR->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create()
			->setTag("texture_type", new StringTag($this->textureType->getValue()));

		if ($this->protection === null) {
			return $nbt;
		}

		return $nbt->setTag("protection", new IntTag($this->protection));
	}
}
