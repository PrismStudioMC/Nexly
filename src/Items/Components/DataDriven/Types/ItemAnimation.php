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

enum ItemAnimation : int
{
	case NONE = 0;
	case EAT = 1;
	case DRINK = 2;
	case BLOCK = 3;
	case BOW = 4;
	case CAMERA = 5;
	case SPEAR = 6;
	case CROSSBOW = 9;
	case SPYGLASS = 10;
	case BRUSH = 12;

	/**
	 * The name of the ItemAnimation.
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * Get the integer value of the ItemAnimation.
	 */
	public function getValue() : int
	{
		return $this->value;
	}

	/**
	 * Create an ItemAnimation from an integer value.
	 */
	public static function fromInt(int $value) : ItemAnimation
	{
		return match ($value) {
			1 => self::EAT,
			2 => self::DRINK,
			default => self::NONE,
		};
	}
}
