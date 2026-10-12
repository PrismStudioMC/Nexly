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
use Nexly\Blocks\Components\Types\Material;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaterialInstancesBlockComponent extends BlockComponent
{
	/**
	 * @param Material[] $materials
	 */
	public function __construct(
		protected array $materials,
		protected ?CompoundTag $mappings = null
	) {
		$this->mappings ??= CompoundTag::create();
	}

	/**
	 * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
	 */
	public function getName() : string
	{
		return BlockComponentIds::MATERIAL_INSTANCES;
	}

	/**
	 * Returns the component in the correct NBT format supported by the client.
	 */
	public function toNBT() : CompoundTag
	{
		$materials = CompoundTag::create();
		foreach ($this->materials as $material) {
			$materials->setTag($material->getTarget(), $material->toNBT());
		}

		return CompoundTag::create()
			->setTag("mappings", $this->mappings)
			->setTag("materials", $materials);
	}
}
