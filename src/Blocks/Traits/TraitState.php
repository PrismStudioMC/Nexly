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

namespace Nexly\Blocks\Traits;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Tag;
use function array_map;
use function array_unique;
use function array_values;
use function in_array;
use function str_replace;

class TraitState
{
	/** @var string[] */
	protected array $enabledTraits;

	public function __construct(string ...$states)
	{
		$this->enabledTraits = array_values(array_unique($states));

		$directionsCount = 0;
		if ($this->isCardinal()) {
			$directionsCount++;
		}
		if ($this->isFacing()) {
			$directionsCount++;
		}
		if (in_array(TraitStateId::SIXTEEN_WAY_ROTATION, $this->enabledTraits, true)) {
			$directionsCount++;
		}

		if ($directionsCount > 1) {
			throw new \InvalidArgumentException("A trait cannot combine cardinal_direction, facing_direction, or sixteen_way_rotation. Choose only one.");
		}
	}

	/**
	 * @return string[]
	 */
	public function getEnabledTraits() : array
	{
		return $this->enabledTraits;
	}

	public function isCardinal() : bool
	{
		return in_array(TraitStateId::CARDINAL_DIRECTION, $this->enabledTraits, true);
	}

	public function isFacing() : bool
	{
		return in_array(TraitStateId::FACING_DIRECTION, $this->enabledTraits, true);
	}

	public function getNames() : array
	{
		$names = [];
		foreach ($this->enabledTraits as $stateValue) {
			if ($stateValue === TraitStateId::CORNER_AND_CARDINAL_DIRECTION) {
				$names[] = 'minecraft:corner';
				$names[] = 'minecraft:cardinal_direction';
			} else {
				$names[] = 'minecraft:' . $stateValue;
			}
		}
		return array_values(array_unique($names));
	}

	/**
	 * @param ListTag $nbt
	 */
	public static function fromNBT(Tag $nbt) : self
	{
		$rawNames = [];
		/** @var StringTag $tag */
		foreach ($nbt as $tag) {
			$rawNames[] = $tag->getValue();
		}

		$hasCorner = in_array('minecraft:corner', $rawNames, true);
		$hasCardinal = in_array('minecraft:cardinal_direction', $rawNames, true);

		$states = [];
		foreach ($rawNames as $rawName) {
			if ($rawName === 'minecraft:corner') {
				continue;
			}
			if ($rawName === 'minecraft:cardinal_direction' && $hasCorner) {
				$states[] = TraitStateId::CORNER_AND_CARDINAL_DIRECTION;
				continue;
			}

			$states[] = str_replace('minecraft:', '', $rawName);
		}

		return new self(...$states);
	}

	public function toNBT() : ListTag
	{
		return new ListTag(array_map(fn (string $name) => new StringTag($name), $this->getEnabledTraits()), NBT::TAG_String);
	}
}
