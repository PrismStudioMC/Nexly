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

use Nexly\Blocks\Vanilla\HeadBlock;
use pocketmine\block\Block;
use pocketmine\block\Farmland;
use pocketmine\block\Fence;
use pocketmine\block\Furnace;
use pocketmine\block\GlassPane;
use pocketmine\block\Hopper;
use pocketmine\block\Ladder;
use pocketmine\block\Lever;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\utils\Ageable;
use pocketmine\block\utils\LeverFacing;
use pocketmine\block\utils\SlabType;
use pocketmine\block\utils\StairShape;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\math\Facing;
use ReflectionClass;
use RuntimeException;

class DeserializerHelper
{
	public static function decodeAgeable(Ageable $block, BlockStateReader $in) : Block
	{
		if (!$block instanceof Block) {
			throw new RuntimeException("Deserializer requires Block object.");
		}

		return $block->setAge($in->readBoundedInt(StateNames::AGE, 0, $block::MAX_AGE));
	}

	public static function decodeStair(Stair $block, BlockStateReader $in) : Stair
	{
		return $block
			->setUpsideDown($in->readBool(StateNames::UPSIDE_DOWN_BIT))
			->setFacing($in->read5MinusHorizontalFacing())
			->setShape(match ($in->readString(StateNames::MC_CORNER)) {
				StateValues::MC_CORNER_INNER_LEFT => StairShape::INNER_LEFT(),
				StateValues::MC_CORNER_INNER_RIGHT => StairShape::INNER_RIGHT(),
				StateValues::MC_CORNER_OUTER_LEFT => StairShape::OUTER_LEFT(),
				StateValues::MC_CORNER_OUTER_RIGHT => StairShape::OUTER_RIGHT(),
				default => StairShape::STRAIGHT,
			});
	}

	public static function decodeSlab(Slab $block, BlockStateReader $in) : Slab
	{
		return $block
			->setSlabType(match ($in->readString(StateNames::MC_VERTICAL_HALF)) {
				StateValues::MC_VERTICAL_HALF_BOTTOM => SlabType::BOTTOM,
				StateValues::MC_VERTICAL_HALF_TOP => SlabType::TOP,
				"double" => SlabType::DOUBLE,
				default => throw new RuntimeException("Invalid slab type"),
			});
	}

	public static function decodeFence(Fence $block, BlockStateReader $in) : Fence
	{
		$co = [];

		if ($in->readBool("minecraft:connection_north")) {
			$co[Facing::NORTH] = Facing::NORTH;
		}
		if ($in->readBool("minecraft:connection_south")) {
			$co[Facing::SOUTH] = Facing::SOUTH;
		}
		if ($in->readBool("minecraft:connection_west")) {
			$co[Facing::WEST] = Facing::WEST;
		}
		if ($in->readBool("minecraft:connection_east")) {
			$co[Facing::EAST] = Facing::EAST;
		}

		$reflection = new ReflectionClass(Fence::class);
		$connections = $reflection->getProperty("connections");
		$connections->setValue($block, $co);
		return $block;
	}

	public static function decodeHopper(Hopper $block, BlockStateReader $in) : Hopper
	{
		return $block
			->setPowered($in->readBool(StateNames::TOGGLE_BIT))
			->setFacing($in->readFacingWithoutUp());
	}

	public static function decodeFurnace(Furnace $block, BlockStateReader $in) : Furnace
	{
		return $block->setFacing($in->readCardinalHorizontalFacing());
	}

	public static function decodeHeadBlock(HeadBlock $block, BlockStateReader $in) : HeadBlock
	{
		return $block
			->setRotation($in->readInt(StateNames::ROTATION))
			->setFacing($in->readFacingWithoutDown());
	}

	public static function decodeLadder(Ladder $block, BlockStateReader $in) : Ladder
	{
		return $block
			->setFacing($in->readHorizontalFacing());
	}

	public static function decodeFarmland(Farmland $block, BlockStateReader $in) : Farmland
	{
		return $block
			->setWetness($in->readInt(StateNames::MOISTURIZED_AMOUNT));
	}

	public static function decodeGlassPane(GlassPane $block, BlockStateReader $in) : GlassPane
	{
		$co = [];

		if ($in->readInt(StateNames::MC_CONNECTION_NORTH)) {
			$co[Facing::NORTH] = Facing::NORTH;
		}
		if ($in->readInt(StateNames::MC_CONNECTION_SOUTH)) {
			$co[Facing::SOUTH] = Facing::SOUTH;
		}
		if ($in->readInt(StateNames::MC_CONNECTION_WEST)) {
			$co[Facing::WEST] = Facing::WEST;
		}
		if ($in->readInt(StateNames::MC_CONNECTION_EAST)) {
			$co[Facing::EAST] = Facing::EAST;
		}

		$reflection = new ReflectionClass(GlassPane::class);
		$connections = $reflection->getProperty("connections");
		$connections->setValue($block, $co);

		return $block;
	}

	public static function decodeLever(Lever $block, BlockStateReader $in) : Lever
	{
		return (clone $block)
			->setActivated($in->readBool(StateNames::OPEN_BIT))
			->setFacing(match($in->readString(StateNames::LEVER_DIRECTION)) {
				StateValues::LEVER_DIRECTION_DOWN_NORTH_SOUTH => LeverFacing::DOWN_AXIS_Z,
				StateValues::LEVER_DIRECTION_DOWN_EAST_WEST => LeverFacing::DOWN_AXIS_X,
				StateValues::LEVER_DIRECTION_UP_NORTH_SOUTH => LeverFacing::UP_AXIS_Z,
				StateValues::LEVER_DIRECTION_UP_EAST_WEST => LeverFacing::UP_AXIS_X,
				StateValues::LEVER_DIRECTION_NORTH => LeverFacing::NORTH,
				StateValues::LEVER_DIRECTION_SOUTH => LeverFacing::SOUTH,
				StateValues::LEVER_DIRECTION_WEST => LeverFacing::WEST,
				StateValues::LEVER_DIRECTION_EAST => LeverFacing::EAST,
				default => throw new RuntimeException("Invalid lever direction"),
			});
	}
}
