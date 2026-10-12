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

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class DurabilityThreshold
{
	public function __construct(
		protected int $durability,
		protected ?string $sound = null,
		protected ?string $particle = null,
	) {
	}

	public function getDurability() : int
	{
		return $this->durability;
	}

	public function getSound() : ?string
	{
		return $this->sound;
	}

	public function getParticle() : ?string
	{
		return $this->particle;
	}

	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create()
			->setTag("durability", new IntTag($this->durability));

		if ($this->sound !== null) {
			$nbt->setTag("sound", new StringTag($this->sound));
		}

		if ($this->particle !== null) {
			$nbt->setTag("particle", new StringTag($this->particle));
		}

		return $nbt;
	}
}
