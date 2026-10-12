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

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use function array_map;
use function array_values;

class LiquidRule
{
	/**
	 * @param array<string> $stopsLiquidFlowingFromDirection
	 */
	public function __construct(
		protected bool              $canContainLiquid,
		protected string            $liquidType = LiquidType::WATER,
		protected string            $onLiquidTouches = LiquidTouchAction::BLOCKING,
		protected array             $stopsLiquidFlowingFromDirection = [],
		protected bool              $useLiquidClipping = false,
	) {
	}

	public function toNBT() : CompoundTag
	{
		$liquidsDirection = array_map(
			fn (string $direction) => new StringTag($direction),
			$this->stopsLiquidFlowingFromDirection
		);
		return CompoundTag::create()
			->setTag("canContainLiquid", new ByteTag($this->canContainLiquid))
			->setTag("liquidType", new StringTag($this->liquidType))
			->setTag("onLiquidTouches", new StringTag($this->onLiquidTouches))
			->setTag("stopsLiquidFromDirection", new ListTag(array_values($liquidsDirection), NBT::TAG_String))
			->setTag("useLiquidClipping", new ByteTag($this->useLiquidClipping));
	}
}
