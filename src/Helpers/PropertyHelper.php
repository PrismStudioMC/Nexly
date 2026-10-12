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

namespace Nexly\Helpers;

use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\data\bedrock\block\convert\property\ValueMappings;
use function array_keys;

class PropertyHelper
{
	public const BOOL = [false, true];
	public const MC_CORNER = [
		StateValues::MC_CORNER_NONE,
		StateValues::MC_CORNER_INNER_LEFT,
		StateValues::MC_CORNER_INNER_RIGHT,
		StateValues::MC_CORNER_OUTER_LEFT,
		StateValues::MC_CORNER_OUTER_RIGHT
	];
	public const MC_VERTICAL_HALF = [
		StateValues::MC_VERTICAL_HALF_BOTTOM,
		StateValues::MC_VERTICAL_HALF_TOP,
		"double"
	];
	public const MC_CARDINAL_DIRECTION = [
		StateValues::MC_CARDINAL_DIRECTION_NORTH,
		StateValues::MC_CARDINAL_DIRECTION_SOUTH,
		StateValues::MC_CARDINAL_DIRECTION_WEST,
		StateValues::MC_CARDINAL_DIRECTION_EAST
	];
	public const WALL_CONNECTION_TYPE = [
		StateValues::WALL_CONNECTION_TYPE_NORTH_NONE,
		StateValues::WALL_CONNECTION_TYPE_NORTH_SHORT,
		StateValues::WALL_CONNECTION_TYPE_NORTH_TALL,
	];
	public const LEVER_DIRECTION = [
		StateValues::LEVER_DIRECTION_DOWN_NORTH_SOUTH,
		StateValues::LEVER_DIRECTION_DOWN_EAST_WEST,
		StateValues::LEVER_DIRECTION_UP_NORTH_SOUTH,
		StateValues::LEVER_DIRECTION_UP_EAST_WEST,
		StateValues::LEVER_DIRECTION_NORTH,
		StateValues::LEVER_DIRECTION_SOUTH,
		StateValues::LEVER_DIRECTION_WEST,
		StateValues::LEVER_DIRECTION_EAST
	];

	public static function get(string $name) : array
	{
		return array_keys(ValueMappings::getInstance()->$name->getRawToValueMap());
	}
}
