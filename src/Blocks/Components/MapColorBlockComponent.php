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
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use function is_array;
use function sprintf;
use function str_starts_with;
use function strtoupper;

#[Attribute(Attribute::TARGET_CLASS)]
class MapColorBlockComponent extends BlockComponent
{
	protected string $hexColor;

	/**
	 * @param string|array{int,int,int} $color Hex #RRGGBB or three RGB channels.
	 */
	public function __construct(
		string|array $color,
		protected ?string $tintMethod = null
	) {
		if (is_array($color)) {
			$this->hexColor = sprintf('#%02X%02X%02X', ...$color);
		} else {
			$color = strtoupper($color);
			$this->hexColor = str_starts_with($color, '#') ? $color : '#' . $color;
		}
	}

	public function getName() : string
	{
		return BlockComponentIds::MAP_COLOR;
	}

	public function toNBT() : StringTag|CompoundTag
	{
		if ($this->tintMethod === null) {
			return new StringTag($this->hexColor);
		}

		return CompoundTag::create()
			->setString('color', $this->hexColor)
			->setString('tint_method', $this->tintMethod);
	}
}
