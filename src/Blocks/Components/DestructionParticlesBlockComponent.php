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
use Nexly\Blocks\Components\Types\TintMethod;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructionParticlesBlockComponent extends BlockComponent
{
	public function __construct(
		protected int $particleCount = 100,
		protected ?string $texture = null,
		protected string $tintMethod = TintMethod::NONE,
	) {
		if ($particleCount < 0 || $particleCount > 255) {
			throw new InvalidArgumentException('Destruction particle count must be between 0 and 255.');
		}
		if ($texture === '') {
			throw new InvalidArgumentException('Particle texture cannot be empty when provided.');
		}
	}

	public function getName() : string
	{
		return BlockComponentIds::DESTRUCTION_PARTICLES;
	}

	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create()
			->setTag('particle_count', new IntTag($this->particleCount))
			->setTag('tint_method', new StringTag($this->tintMethod));

		if ($this->texture !== null) {
			$nbt->setTag('texture', new StringTag($this->texture));
		}
		return $nbt;
	}
}
