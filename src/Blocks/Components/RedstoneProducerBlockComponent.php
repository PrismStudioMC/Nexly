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
use Nexly\Blocks\Components\Types\Face;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use function array_map;
use function array_unique;
use function count;

#[Attribute(Attribute::TARGET_CLASS)]
class RedstoneProducerBlockComponent extends BlockComponent
{
	public function __construct(
		protected int $power,
		protected ?array $connectedFaces = null,
		protected ?string $stronglyPoweredFace = null,
		protected bool $transformRelative = false,
	) {
		if ($power < 0 || $power > 15) {
			throw new InvalidArgumentException('Redstone power must be from 0 to 15.');
		}

		$this->connectedFaces ??= Face::ALL;
		if (count(array_unique($this->connectedFaces)) !== count($this->connectedFaces)) {
			throw new InvalidArgumentException('Connected redstone faces must be unique.');
		}
	}

	public function getName() : string
	{
		return BlockComponentIds::REDSTONE_PRODUCER;
	}

	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create()
			->setTag('power', new IntTag($this->power))
			->setTag('connected_faces', new ListTag(array_map(static fn (string $face) => new StringTag($face), $this->connectedFaces), NBT::TAG_String))
			->setTag('transform_relative', new ByteTag($this->transformRelative));

		if ($this->stronglyPoweredFace !== null) {
			$nbt->setString('strongly_powered_face', $this->stronglyPoweredFace);
		}
		return $nbt;
	}
}
