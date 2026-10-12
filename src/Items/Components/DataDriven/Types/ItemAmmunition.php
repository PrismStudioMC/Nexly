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

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\item\Item;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

class ItemAmmunition
{
	public function __construct(
		protected string $item,
		protected bool $searchInventory = true,
		protected bool $useInCreative = true,
		protected bool $useOffHand = true
	) {
	}

	/**
	 * Create an ItemAmmunition component from an Item instance.
	 */
	public static function from(
		Item $item,
		bool $searchInventory = true,
		bool $useInCreative = true,
		bool $useOffHand = true
	) : self {
		[$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
		return new self(
			$converter->getItemTypeDictionary()->fromIntId($rid),
			$searchInventory,
			$useInCreative,
			$useOffHand
		);
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("item", new StringTag($this->item))
			->setTag("search_inventory", new ByteTag($this->searchInventory))
			->setTag("use_in_creative", new ByteTag($this->useInCreative))
			->setTag("use_offhand", new ByteTag($this->useOffHand));
	}
}
