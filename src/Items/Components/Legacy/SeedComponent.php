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

namespace Nexly\Items\Components\Legacy;

use Attribute;
use Nexly\Items\Components\Legacy\Types\LegacyFace;
use pocketmine\block\Block;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function array_values;
use function str_replace;
use function strtolower;

#[Attribute(Attribute::TARGET_CLASS)]
class SeedComponent extends LegacyItemComponent
{
	/**
	 * The name of the component.
	 */
	public static function getName() : string
	{
		return LegacyComponentIds::SEED->getValue();
	}

	/**
	 * Create a SeedComponent from blocks.
	 */
	public static function fromBlocks(Block $result, Block ...$blocks) : self
	{
		return new self(
			GlobalBlockStateHandlers::getSerializer()->serialize($result->getStateId())->getName(),
			array_map(fn (Block $block) => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(), $blocks),
		);
	}

	/**
	 * Create a SeedComponent from tags.
	 */
	public static function fromTags(Block $result, array $tags) : self
	{
		$plantAtNames = [];
		$serializer = GlobalBlockStateHandlers::getSerializer();

		foreach (RuntimeBlockStateRegistry::getInstance()->getAllKnownStates() as $state) {
			foreach ($tags as $tag) {
				if ($state->hasTypeTag($tag)) {
					$name = $serializer->serialize($state->getStateId())->getName();
					$plantAtNames[$name] = $name;
					break;
				}
			}
		}

		return new self(
			$serializer->serialize($result->getStateId())->getName(),
			array_values($plantAtNames)
		);
	}

	public function __construct(
		protected string $result,
		protected array $plantAt = [],
		protected LegacyFace $plantFace = LegacyFace::UP,
	) {
		$this->result = str_replace(["minecraft:", "grass_block", "air"], ["", "grass", "light_block"], strtolower($this->result));
		$this->plantAt = array_map(fn (string $block) => str_replace(["minecraft:", "grass_block"], ["", "grass"], strtolower($block)), $this->plantAt);
	}

	/**
	 * Get the maximum damage value.
	 */
	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("crop_result", new StringTag($this->result))
			->setTag("plant_at_face", new StringTag($this->plantFace->getValue()))
			->setTag("plant_at_any_solid_surface", new ByteTag(empty($this->plantAt)))
			->setTag("plant_at", new ListTag(array_map(fn (string $block) => new StringTag($block), $this->plantAt), NBT::TAG_String));
	}
}
