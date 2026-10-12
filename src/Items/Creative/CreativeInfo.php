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

namespace Nexly\Items\Creative;

use Attribute;
use BackedEnum;
use pocketmine\inventory\CreativeCategory;

#[Attribute(Attribute::TARGET_CLASS)]
class CreativeInfo
{
	public function __construct(
		protected ?CreativeCategory $category,
		protected CreativeGroup|BackedEnum|null $group = null,
		protected bool $isHidden = false,
	) {
	}

	public function getCategory() : ?CreativeCategory
	{
		return $this->category;
	}

	public function getGroup() : BackedEnum|CreativeGroup|null
	{
		return $this->group;
	}

	public function isHidden() : bool
	{
		return $this->isHidden;
	}
}
