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

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use function array_map;
use function count;
use function strlen;

#[Attribute(Attribute::TARGET_CLASS)]
class CraftingTableBlockComponent extends BlockComponent
{
	public function __construct(
		protected array $craftingTags,
		protected ?string $tableName = null
	) {
		if (count($craftingTags) > 64) {
			throw new InvalidArgumentException('At most 64 crafting tags are allowed.');
		}
		foreach ($craftingTags as $tag) {
			if (strlen($tag) > 64) {
				throw new InvalidArgumentException('Crafting tags are limited to 64 characters.');
			}
		}

		if ($tableName === '') {
			throw new InvalidArgumentException('Crafting table name cannot be empty when provided.');
		}
	}

	public function getName() : string
	{
		return BlockComponentIds::CRAFTING_TABLE;
	}

	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create();

		if ($this->tableName !== null) {
			$nbt->setTag('table_name', new StringTag($this->tableName));
		}
		return $nbt
			->setTag('crafting_tags', new ListTag(array_map(fn (string $value) => new StringTag($value), $this->craftingTags), NBT::TAG_String));
	}
}
