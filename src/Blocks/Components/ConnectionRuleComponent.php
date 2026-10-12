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
use Nexly\Blocks\Components\Types\ConnectionType;
use Nexly\Blocks\Components\Types\HorizontalFace;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class ConnectionRuleComponent extends BlockComponent
{
	/**
	 * @param list<string> $enabledDirections
	 */
	public function __construct(
		protected string $from = ConnectionType::ALL,
		protected array $enabledDirections = [HorizontalFace::SOUTH, HorizontalFace::NORTH, HorizontalFace::EAST, HorizontalFace::WEST],
	) {
	}

	/**
	 * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
	 */
	public function getName() : string
	{
		return BlockComponentIds::CONNECTION_RULE;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("accepts_connections_from", new StringTag($this->from))
			->setTag("enabled_directions", new ListTag(array_map(fn (string $direction) : StringTag => new StringTag($direction), $this->enabledDirections), NBT::TAG_String));
	}
}
