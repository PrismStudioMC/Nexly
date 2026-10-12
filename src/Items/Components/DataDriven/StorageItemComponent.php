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
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageItemComponent extends DataDrivenItemComponent
{
	public function __construct(
		protected int $capacity,
		protected bool $allowNested = true,
		protected array $allowedItems = [],
		protected array $bannedItems = [],
	) {
		if ($this->capacity < 0) {
			throw new \InvalidArgumentException("Capacity must be a non-negative integer.");
		}

		if ($this->capacity > 64) {
			throw new \InvalidArgumentException("Capacity must not exceed 64.");
		}
	}

	/**
	 * Create a StorageItemComponent.
	 *
	 * @param Item[] $allowedItems
	 * @param Item[] $bannedItems
	 */
	public static function from(
		int $capacity,
		bool $allowNested = true,
		array $allowedItems = [],
		array $bannedItems = []
	) : self {

		$processItem = function (Item $item) : string {
			[$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
			return $converter->getItemTypeDictionary()->fromIntId($rid);
		};

		return new self(
			$capacity,
			$allowNested,
			array_map($processItem, $allowedItems),
			array_map($processItem, $bannedItems)
		);
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::STORAGE->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("max_slots", new IntTag($this->capacity))
			->setTag("allow_nested_storage_items", new ByteTag($this->allowNested))
			->setTag("allowed_items", new ListTag(array_map(fn (string $item) => new StringTag($item), $this->allowedItems), NBT::TAG_String))
			->setTag("banned_items", new ListTag(array_map(fn (string $item) => new StringTag($item), $this->bannedItems), NBT::TAG_String));
	}
}
