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

namespace Nexly\Blocks\Mappers;

use Nexly\Blocks\BlockBuilder as Builder;
use Nexly\Blocks\Components\CollisionBoxBlockComponent;
use Nexly\Blocks\Components\SelectionBoxBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;

class WallBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$none = StateValues::WALL_CONNECTION_TYPE_NORTH_NONE;

		$hasN = $states[StateNames::WALL_CONNECTION_TYPE_NORTH] !== $none;
		$hasS = $states[StateNames::WALL_CONNECTION_TYPE_SOUTH] !== $none;
		$hasW = $states[StateNames::WALL_CONNECTION_TYPE_WEST] !== $none;
		$hasE = $states[StateNames::WALL_CONNECTION_TYPE_EAST] !== $none;
		$hasPost = $states[StateNames::WALL_POST_BIT];

		$minX = $hasE ? -8 : -4;
		$maxX = $hasW ? 8 : 4;
		$minZ = $hasN ? -8 : -4;
		$maxZ = $hasS ? 8 : 4;

		if (!$hasPost) {
			if ($hasN && $hasS && !$hasW && !$hasE) {
				$minX = -3;
				$maxX = 3;
			} elseif (!$hasN && !$hasS && $hasW && $hasE) {
				$minZ = -3;
				$maxZ = 3;
			}
		}

		$boxCollision = new BoxCollision(
			new Vector3($minX, 0, $minZ),
			new Vector3($maxX - $minX, 24, $maxZ - $minZ)
		);
		$boxSelection = new BoxCollision(
			new Vector3($minX, 0, $minZ),
			new Vector3($maxX - $minX, 13, $maxZ - $minZ)
		);

		$expr = MolangHelper::and(
			MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_NORTH, $states[StateNames::WALL_CONNECTION_TYPE_NORTH]),
			MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_SOUTH, $states[StateNames::WALL_CONNECTION_TYPE_SOUTH]),
			MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_WEST, $states[StateNames::WALL_CONNECTION_TYPE_WEST]),
			MolangHelper::stateEquals(StateNames::WALL_CONNECTION_TYPE_EAST, $states[StateNames::WALL_CONNECTION_TYPE_EAST]),
			MolangHelper::stateEquals(StateNames::WALL_POST_BIT, $hasPost)
		);
		return Permutation::create($expr)
			->addComponent(new CollisionBoxBlockComponent(true, [$boxCollision]))
			->addComponent(new SelectionBoxBlockComponent(true, [$boxSelection]));
	}
}
