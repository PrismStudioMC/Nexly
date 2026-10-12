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

namespace Nexly\Mappings;

use Nexly\Items\ItemBuilder;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use ReflectionException;

class ItemMappings
{
	use SingletonTrait;

	/** @var array<string, array> Mapping of item string IDs to their numeric IDs. */
	protected array $mappings = [];

	public function getMappings() : array
	{
		return $this->mappings;
	}

	public function setMappings(array $mappings) : void
	{
		$this->mappings = $mappings;
	}

	/**
	 * Registers a new item mapping.
	 */
	public function registerMapping(ItemBuilder $builder, ItemTypeEntry $entry) : void
	{
		if (isset($this->mappings[$entry->getStringId()])) {
			throw new \InvalidArgumentException("Item mapping for " . $entry->getStringId() . " is already registered");
		}

		$this->mappings[$entry->getStringId()] = ["builder" => $builder, "entry" => $entry];

		try {
			$this->registerEntry($entry);
		} catch (ReflectionException $e) {
			throw new \RuntimeException("Failed to register item entry: " . $e->getMessage(), 0, $e);
		}
	}

	/**
	 * Retrieves the mapping for a given item string ID.
	 */
	public function getMapping(string $stringId) : ?array
	{
		return $this->mappings[$stringId] ?? null;
	}

	/**
	 * Registers a new ItemTypeEntry to the TypeConverter's item type dictionary.
	 *
	 * @throws ReflectionException
	 */
	public static function registerEntry(ItemTypeEntry $entry) : void
	{
		$stringId = $entry->getStringId();
		$numericId = $entry->getNumericId();

		$dictionary = TypeConverter::getInstance()->getItemTypeDictionary();
		$reflection = new \ReflectionClass($dictionary);

		$intToString = $reflection->getProperty("intToStringIdMap");
		/** @var int[] $value */
		$value = $intToString->getValue($dictionary);
		$intToString->setValue($dictionary, $value + [$numericId => $stringId]);

		$stringToInt = $reflection->getProperty("stringToIntMap");
		/** @var int[] $value */
		$value = $stringToInt->getValue($dictionary);
		$stringToInt->setValue($dictionary, $value + [$stringId => $numericId]);

		$itemTypes = $reflection->getProperty("itemTypes");
		$value = $itemTypes->getValue($dictionary);
		$value[] = $entry;
		$itemTypes->setValue($dictionary, $value);
	}
}
