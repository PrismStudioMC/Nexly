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
use Nexly\Blocks\Components\Types\CullingLayer;
use Nexly\Blocks\Components\Types\CullingShape;
use Nexly\Blocks\Components\Types\Geometry;
use Nexly\Blocks\Components\Types\NWayVisualRotation;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use function array_map;
use function count;
use function is_array;
use function is_bool;
use function strval;

#[Attribute(Attribute::TARGET_CLASS)]
class GeometryBlockComponent extends BlockComponent
{
	public function __construct(
		protected string                 $identifier = Geometry::FULL_BLOCK,
		protected string                 $cullingType = "",
		protected string                 $layer = CullingLayer::UNDEFINED,
		protected string                 $cullingShape = CullingShape::EMPTY,
		protected ?NWayVisualRotation    $nWayVisualRotation = null,
		protected bool|array             $uvLock = false,
		protected bool                   $ignoreGeometryForIsSolid = true,
		protected bool                   $needsLegacyTopRotation = false,
		protected bool                   $useBlockTypeLightAbsorption = false,
		protected ?CompoundTag                    $boneVisibility = null
	) {
		$this->boneVisibility ??= CompoundTag::create();
		if (is_array($uvLock) && count($uvLock) > 64) {
			throw new \InvalidArgumentException("UV lock cannot contain more than 64 bone names.");
		}
	}

	/**
	 * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
	 */
	public function getName() : string
	{
		return BlockComponentIds::GEOMETRY;
	}

	/**
	 * @return $this
	 */
	public function add(string $boneName, bool|string $visibility) : self
	{
		$this->boneVisibility->setTag(
			$boneName,
			CompoundTag::create()
				->setString("expression", is_bool($visibility) ? strval((int) $visibility) : $visibility)
				->setShort("version", 12)
		);
		return $this;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		$nbt = CompoundTag::create()
			->setTag("bone_visibility", $this->boneVisibility)
			->setTag("identifier", new StringTag($this->identifier))
			->setTag("culling", new StringTag($this->cullingType))
			->setTag("culling_layer", new StringTag($this->layer))
			->setTag("ignoreGeometryForIsSolid", new ByteTag($this->ignoreGeometryForIsSolid))
			->setTag("needsLegacyTopRotation", new ByteTag($this->needsLegacyTopRotation))
			->setTag("useBlockTypeLightAbsorption", new ByteTag($this->useBlockTypeLightAbsorption))
			->setTag("culling_shape", new StringTag($this->cullingShape));

		if ($this->nWayVisualRotation !== null) {
			$nbt->setTag("n_way_visual_rotation", new StringTag($this->nWayVisualRotation));
		}

		$nbt->setTag(
			"uv_lock",
			is_bool($this->uvLock)
			? new ByteTag($this->uvLock)
			: new ListTag(array_map(fn (string $bone) : StringTag => new StringTag($bone), $this->uvLock), NBT::TAG_String)
		);
		return $nbt;
	}
}
