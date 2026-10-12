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

namespace Nexly\Items\Components\DataDriven\Types;

use function strtolower;

enum ItemRarity : string
{
	case COMMON = "common";
	case UNCOMMON = "uncommon";
	case RARE = "rare";
	case EPIC = "epic";

	/**
	 * The name of the rarity.
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * The value of the rarity.
	 */
	public function getValue() : string
	{
		return $this->value;
	}

	/**
	 * Create an ItemRarity from a string name.
	 */
	public static function fromName(string $name) : self
	{
		return match (strtolower($name)) {
			'common' => self::COMMON,
			'uncommon' => self::UNCOMMON,
			'rare' => self::RARE,
			'epic' => self::EPIC,
			default => throw new \InvalidArgumentException("Invalid item rarity name: $name"),
		};
	}
}
