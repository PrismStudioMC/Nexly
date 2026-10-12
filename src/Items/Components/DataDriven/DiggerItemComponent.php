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
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use function array_map;
use function implode;
use function is_string;

#[Attribute(Attribute::TARGET_CLASS)]
class DiggerItemComponent extends DataDrivenItemComponent
{
	/**
	 * @param CompoundTag[] $diggers
	 */
	public function __construct(
		protected bool $efficiency = false,
		protected array $diggers = []
	) {
	}

	public static function getName() : string
	{
		return DataDrivenComponentIds::DIGGER->getValue();
	}

	/**
	 * @return $this
	 */
	public function addBlock(Block $block, int $speed) : self
	{
		$blockName = GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName();

		$this->diggers[] = CompoundTag::create()
			->setTag("block", CompoundTag::create()->setString("name", $blockName))
			->setInt("speed", $speed);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function addBlocks(int $speed, Block ...$blocks) : self
	{
		foreach ($blocks as $block) {
			$this->addBlock($block, $speed);
		}
		return $this;
	}

	/**
	 * @param string[]|string $tags
	 */
	public function addTag(array|string $tags, int $speed) : self
	{
		$tagsArray = is_string($tags) ? [$tags] : $tags;

		$molang = "query.any_tag(" . implode(", ", array_map(fn (string $tag) => "'$tag'", $tagsArray)) . ")";
		$this->diggers[] = CompoundTag::create()
			->setTag("block", CompoundTag::create()->setString("tags", $molang))
			->setInt("speed", $speed);

		return $this;
	}

	public function toNBT() : CompoundTag
	{
		return CompoundTag::create()
			->setTag("on_dig", CompoundTag::create())
			->setByte("use_efficiency", (int) $this->efficiency) // On utilise (int) $this->efficiency
			->setTag("destroy_speeds", new ListTag($this->diggers, NBT::TAG_Compound));
	}
}
