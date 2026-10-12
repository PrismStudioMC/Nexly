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

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use function is_array;
use function is_int;

class NWayVisualRotation
{
	/**
	 * @param array<string, string>|list<array{string, string}> $axes
	 */
	public function __construct(
		protected array $axes
	) {
		$normalized = [];

		foreach ($axes as $axis => $state) {
			if (is_int($axis) && is_array($state)) {
				[$axis, $state] = $state;
			}

			$normalized[$axis] = (string) $state;
		}

		$this->axes = $normalized;
	}

	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create();
		foreach ($this->axes as $axis => $state) {
			$nbt->setTag($axis, new StringTag($state));
		}
		return $nbt;
	}
}
