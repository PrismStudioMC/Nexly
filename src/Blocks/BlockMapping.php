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

namespace Nexly\Blocks;

use pocketmine\block\Block;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;

class BlockMapping
{
	public function __construct(
		protected BlockBuilder $builder,
		protected BlockPaletteEntry $entry,
	) {
	}

	public function getBuilder() : BlockBuilder
	{
		return $this->builder;
	}

	public function getStringId() : string
	{
		return $this->builder->getStringId();
	}

	public function getBlock() : Block
	{
		$builder = $this->getBuilder();
		return ($builder->getBlock())($builder->getNumericId());
	}

	public function getEntry() : BlockPaletteEntry
	{
		return $this->entry;
	}
}
