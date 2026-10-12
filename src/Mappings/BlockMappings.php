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

use Nexly\Blocks\BlockMapping;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\utils\SingletonTrait;
use function count;

class BlockMappings
{
	use SingletonTrait;

	/** @var array<string, BlockMapping> Mapping of block string IDs to their BlockMapping instances. */
	protected array $mappings = [];
	protected array $entries = [];

	public function nextRuntimeId() : int
	{
		return 10000 + count($this->entries);
	}

	public function getMappings() : array
	{
		return $this->mappings;
	}

	public function setMappings(array $mappings) : void
	{
		$this->mappings = $mappings;
	}

	public function getEntries() : array
	{
		return $this->entries;
	}

	public function setEntries(array $entries) : void
	{
		$this->entries = $entries;
	}

	/**
	 * Registers a new block mapping.
	 */
	public function registerMapping(BlockMapping $mapping) : void
	{
		if (isset($this->mappings[$mapping->getStringId()])) {
			throw new \InvalidArgumentException("Item mapping for " . $mapping->getStringId() . " is already registered");
		}

		$this->mappings[$mapping->getStringId()] = $mapping;
		$this->registerEntry($mapping->getEntry());
	}

	/**
	 * Retrieves the mapping for a given block string ID.
	 */
	public function getMapping(string $stringId) : ?BlockMapping
	{
		return $this->mappings[$stringId] ?? null;
	}

	/**
	 * Registers a block palette entry.
	 */
	public function registerEntry(BlockPaletteEntry $entry) : void
	{
		$this->entries[] = $entry;
	}
}
