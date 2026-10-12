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
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use function array_map;
use function is_double;
use function strval;

class ItemRepair
{
	/**
	 * @param string[] $items
	 */
	public function __construct(
		protected float|string   $amount,
		protected array $items,
	) {
		if (is_double($this->amount)) {
			$this->amount = strval($this->amount);
		}
	}

	/**
	 * Create an ItemRepair component from an amount and a list of items.
	 */
	public static function from(float|string $amount, Item ...$items) : ItemRepair
	{
		return new self(
			$amount,
			array_map(function (Item $item) : string {
				[$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
				return $converter->getItemTypeDictionary()->fromIntId($rid);
			}, $items)
		);
	}

	public function getAmount() : float|string
	{
		return $this->amount;
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag(
				"repair_amount",
				CompoundTag::create()
				->setTag("expression", new StringTag($this->amount))
				->setTag("version", new IntTag(0))
			)
			->setTag("items", new ListTag(array_map(fn (string $stringId) => new StringTag($stringId), $this->items), NBT::TAG_String));
	}
}
