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

namespace Nexly\Blocks\Permutations;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use function gettype;
use function is_bool;
use function is_int;
use function is_string;

class BlockProperty
{
	public function __construct(
		protected string $name,
		protected array $values
	) {
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getValues() : array
	{
		return $this->values;
	}

	public function toNBT()
	{
		$values = [];
		foreach ($this->values as $value) {
			$values[] = match (true) {
				is_string($value) => new StringTag($value),
				is_int($value) => new IntTag($value),
				is_bool($value) => new ByteTag($value),
				default => throw new \InvalidArgumentException("Invalid value type for BlockProperty: " . gettype($value)),
			};
		}

		return CompoundTag::create()
			->setTag("name", new StringTag($this->name))
			->setTag("enum", new ListTag($values));
	}
}
