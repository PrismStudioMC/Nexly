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

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Blocks\Components\Types\NbtDescriptor;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;

#[Attribute(Attribute::TARGET_CLASS)]
class EntityPlacerItemComponent extends DataDrivenItemComponent
{
	/**
	 * @param NbtDescriptor[] $dispenseOn
	 * @param NbtDescriptor[] $useOn
	 */
	public function __construct(
		protected string $entity = "none",
		protected array $dispenseOn = [],
		protected array $useOn = [],
	) {
	}

	/**
	 * Create an EntityPlacerItemComponent from the given parameters.
	 *
	 * @param Block[] $dispenseOn
	 * @param Block[] $useOn
	 */
	public static function from(string $entity, array $dispenseOn = [], array $useOn = []) : self
	{
		return new self(
			$entity,
			array_map(fn (Block $block) => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(), $dispenseOn),
			array_map(fn (Block $block) => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(), $useOn)
		);
	}

	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return DataDrivenComponentIds::ENTITY_PLACER->getValue();
	}

	/**
	 * Build the NBT tag for this component.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("entity", new StringTag($this->entity))
			->setTag("dispense_on", new ListTag(array_map(fn (NbtDescriptor $descriptor) => $descriptor->toNBT(), $this->dispenseOn), NBT::TAG_Compound))
			->setTag("use_on", new ListTag(array_map(fn (NbtDescriptor $descriptor) => $descriptor->toNBT(), $this->useOn), NBT::TAG_Compound));
	}
}
