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

namespace Nexly\Items;

use Closure;
use Nexly\Items\Creative\CreativeInfo;
use Nexly\Items\Creative\NexlyCreative;
use Nexly\Mappings\ItemMappings;
use Nexly\Recipes\NexlyRecipes;
use Nexly\Recipes\Types\Recipe;
use pocketmine\data\bedrock\item\SavedItemData;
use pocketmine\data\bedrock\item\upgrade\LegacyItemIdToStringIdMap;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\world\format\io\GlobalItemDataHandlers as ItemDataHandlers;
use function count;
use function explode;
use function str_contains;

abstract class ItemBuilder
{
	protected string $stringId;
	protected ?int $numericId = null;
	protected Item $item;
	protected ?Closure $serializer = null;
	protected ?Closure $deserializer = null;
	protected ?CreativeInfo $creativeInfo = null;

	/**
	 * Get the string identifier for the item.
	 */
	public function getStringId() : string
	{
		return $this->stringId;
	}

	/**
	 * @internal
	 */
	public function getName() : string
	{
		$itemName = $this->getStringId();
		if (str_contains($itemName, ":")) {
			[, $itemName] = explode(":", $itemName, 2);
		}

		return $itemName;
	}

	/**
	 * Set the string identifier for the item.
	 *
	 * @return $this
	 */
	public function setStringId(string $stringId) : self
	{
		if (str_contains("minecraft:", $stringId)) {
			throw new \InvalidArgumentException("Custom item string ID cannot contain the 'minecraft:' namespace.");
		}

		$this->stringId = $stringId;
		return $this;
	}

	/**
	 * Get the numeric ID for the item, if set.
	 */
	public function getNumericId() : int
	{
		return $this->numericId ??= ItemTypeIds::newId();
	}

	/**
	 * Set the numeric ID for the item.
	 *
	 * @return $this
	 */
	public function setNumericId(?int $numericId) : self
	{
		$this->numericId = $numericId;
		return $this;
	}

	public function getItem() : Item
	{
		return $this->item;
	}

	/**
	 * Set the item instance or a closure that returns an item instance.
	 *
	 * @return $this
	 */
	public function setItem(Item|Closure $item) : self
	{
		if ($item instanceof Item) {
			$this->item = $item;
		} else {
			$this->item = ($item)($this->getNumericId());
		}
		return $this;
	}

	/**
	 * Get the custom serializer for the item.
	 */
	public function getSerializer() : ?Closure
	{
		return $this->serializer;
	}

	/**
	 * Set a custom serializer for the item.
	 *
	 * @return $this
	 */
	public function setSerializer(?Closure $serializer) : self
	{
		$this->serializer = $serializer;
		return $this;
	}

	/**
	 * Get the custom deserializer for the item.
	 */
	public function getDeserializer() : ?Closure
	{
		return $this->deserializer;
	}

	/**
	 * Set a custom deserializer for the item.
	 *
	 * @return $this
	 */
	public function setDeserializer(?Closure $deserializer) : self
	{
		$this->deserializer = $deserializer;
		return $this;
	}

	/**
	 * Get the CreativeInfo attribute for the item, if set.
	 */
	public function getCreativeInfo() : ?CreativeInfo
	{
		return $this->creativeInfo;
	}

	/**
	 * Set the CreativeInfo attribute for the item.
	 *
	 * @return $this
	 */
	public function setCreativeInfo(?CreativeInfo $creativeInfo) : self
	{
		$this->creativeInfo = $creativeInfo;
		return $this;
	}

	abstract public static function getVersion() : ItemVersion;

	/**
	 * Build the ItemTypeEntry for the item.
	 */
	abstract public function toNBT() : CompoundTag;

	/**
	 * Register the item with the provided string identifier and callbacks.
	 */
	public function register() : void
	{
		if (!isset($this->item)) {
			throw new \InvalidArgumentException("Item closure must be set before registering");
		}

		$item = $this->getItem();
		ItemDataHandlers::getDeserializer()->map($this->getStringId(), $this->getDeserializer() ?? fn () => clone $item);
		ItemDataHandlers::getSerializer()->map($item, $this->getSerializer() ?? fn () => new SavedItemData($this->getStringId()));
		ItemMappings::getInstance()->registerMapping($this, new ItemTypeEntry(
			$this->getStringId(),
			$this->getNumericId(),
			$this->getVersion()->equals(ItemVersion::DATA_DRIVEN),
			$this->getVersion()->getValue(),
			new CacheableNbt($this->toNBT())
		));

		$identifier = $this->getStringId();
		if (str_contains($identifier, ":")) {
			[, $path] = explode(":", $identifier, 2);
			$identifier = $path;
		}

		StringToItemParser::getInstance()->register($identifier, fn () => clone $item);
		LegacyItemIdToStringIdMap::getInstance()->add($identifier, $item->getTypeId());

		$creativeInfo = $this->getCreativeInfo() ?? NexlyCreative::detectCreativeInfoFrom($item);
		$reflection = new \ReflectionClass($item);

		$attributes = $reflection->getAttributes();
		if (count($attributes) > 0) {
			foreach ($attributes as $attribute) {
				$instance = $attribute->newInstance();
				if ($instance instanceof CreativeInfo) {
					$creativeInfo = $instance;
				} elseif ($instance instanceof Recipe) {
					NexlyRecipes::getInstance()->addRecipe(fn () => $attribute->newInstance());
				}
			}
		}

		NexlyCreative::add($item, $creativeInfo?->getCategory(), $creativeInfo?->getGroup());
	}
}
