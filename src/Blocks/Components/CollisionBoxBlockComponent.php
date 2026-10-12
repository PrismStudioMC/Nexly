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
use Nexly\Blocks\Components\Types\BoxCollision;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use function array_map;
use function count;

#[Attribute(Attribute::TARGET_CLASS)]
class CollisionBoxBlockComponent extends BlockComponent
{
	/**
	 * @param BoxCollision[] $collisions
	 */
	public function __construct(
		protected bool $enabled,
		protected array $collisions = [],
	) {
		if (count($collisions) > 16) {
			throw new InvalidArgumentException("A collision component supports at most 16 boxes.");
		}

		foreach ($collisions as $collision) {
			if (!$collision instanceof BoxCollision) {
				throw new InvalidArgumentException("A collision component supports only BoxCollision.");
			}
		}

		if (empty($this->collisions)) {
			$this->collisions[] = new BoxCollision(new Vector3(-8.0, 0.0, -8.0), new Vector3(16.0, 16.0, 16.0));
		}
	}

	/**
	 * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
	 */
	public function getName() : string
	{
		return BlockComponentIds::COLLISION_BOX;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("enabled", new ByteTag($this->enabled))
			->setTag(
				"boxes",
				new ListTag(
					array_map(
						fn (BoxCollision $collision) => $collision->toNBT(true),
						$this->collisions
					),
					NBT::TAG_Compound
				)
			);
	}
}
