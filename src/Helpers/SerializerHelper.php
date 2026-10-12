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
use pocketmine\block\Farmland;
use pocketmine\block\Fence;
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
use pocketmine\data\bedrock\block\convert\BlockStateWriter as Writer;
use pocketmine\math\Facing;

class SerializerHelper
{
	public static function encodeAgeable(Ageable $block, Writer $out) : Writer
	{
		return $out->writeInt(StateNames::AGE, $block->getAge());
	}

	public static function encodeStair(Stair $block, Writer $out) : Writer
	{
		return $out
			->writeBool(StateNames::UPSIDE_DOWN_BIT, $block->isUpsideDown())
			->write5MinusHorizontalFacing($block->getFacing())
			->writeString(StateNames::MC_CORNER, match($block->getShape()) {
				StairShape::INNER_LEFT => StateValues::MC_CORNER_INNER_LEFT,
				StairShape::INNER_RIGHT => StateValues::MC_CORNER_INNER_RIGHT,
				StairShape::OUTER_LEFT => StateValues::MC_CORNER_OUTER_LEFT,
				StairShape::OUTER_RIGHT => StateValues::MC_CORNER_OUTER_RIGHT,
				default => StateValues::MC_CORNER_NONE,
			});
	}

	public static function encodeSlab(Slab $block, Writer $out) : Writer
	{
		return $out
			->writeString(StateNames::MC_VERTICAL_HALF, match ($block->getSlabType()) {
				SlabType::BOTTOM => StateValues::MC_VERTICAL_HALF_BOTTOM,
				SlabType::TOP => StateValues::MC_VERTICAL_HALF_TOP,
				SlabType::DOUBLE => "double",
			});
	}

	public static function encodeFence(Fence $block, Writer $out) : Writer
	{
		$reflection = new \ReflectionClass(Fence::class);
		$connections = $reflection->getProperty("connections");
		$co = $connections->getValue($block);
		return $out
			->writeBool("minecraft:connection_north", isset($co[Facing::NORTH]))
			->writeBool("minecraft:connection_south", isset($co[Facing::SOUTH]))
			->writeBool("minecraft:connection_west", isset($co[Facing::WEST]))
			->writeBool("minecraft:connection_east", isset($co[Facing::EAST]));
	}

	public static function encodeHopper(Hopper $block, Writer $out) : Writer
	{
		return $out
			->writeBool(StateNames::TOGGLE_BIT, $block->isPowered())
			->writeFacingWithoutUp($block->getFacing());
	}

	public static function encodeHeadBlock(HeadBlock $block, Writer $out) : Writer
	{
		return $out
			->writeFacingWithoutDown($block->getFacing())
			->writeInt(StateNames::ROTATION, $block->getRotation());
	}

	public static function encodeLadder(Ladder $block, Writer $out) : Writer
	{
		return $out
			->writeHorizontalFacing($block->getFacing());
	}

	public static function encodeFarmland(Farmland $block, Writer $out) : Writer
	{
		return $out
			->writeInt(StateNames::MOISTURIZED_AMOUNT, $block->getWetness());
	}

	public static function encodeGlassPane(GlassPane $block, Writer $out) : Writer
	{
		$reflection = new \ReflectionClass(GlassPane::class);
		$connections = $reflection->getProperty("connections");
		$co = $connections->getValue($block);

		return $out
			->writeBool(StateNames::MC_CONNECTION_NORTH, isset($co[Facing::NORTH]))
			->writeBool(StateNames::MC_CONNECTION_SOUTH, isset($co[Facing::SOUTH]))
			->writeBool(StateNames::MC_CONNECTION_WEST, isset($co[Facing::WEST]))
			->writeBool(StateNames::MC_CONNECTION_EAST, isset($co[Facing::EAST]));
	}

	public static function encodeLever(Lever $state, Writer $out) : Writer
	{
		return $out
			->writeBool(StateNames::OPEN_BIT, $state->isActivated())
			->writeString(StateNames::LEVER_DIRECTION, match($state->getFacing()) {
				LeverFacing::DOWN_AXIS_Z => StateValues::LEVER_DIRECTION_DOWN_NORTH_SOUTH,
				LeverFacing::DOWN_AXIS_X => StateValues::LEVER_DIRECTION_DOWN_EAST_WEST,
				LeverFacing::UP_AXIS_Z => StateValues::LEVER_DIRECTION_UP_NORTH_SOUTH,
				LeverFacing::UP_AXIS_X => StateValues::LEVER_DIRECTION_UP_EAST_WEST,
				LeverFacing::NORTH => StateValues::LEVER_DIRECTION_NORTH,
				LeverFacing::SOUTH => StateValues::LEVER_DIRECTION_SOUTH,
				LeverFacing::WEST => StateValues::LEVER_DIRECTION_WEST,
				LeverFacing::EAST => StateValues::LEVER_DIRECTION_EAST,
			});
	}
}
