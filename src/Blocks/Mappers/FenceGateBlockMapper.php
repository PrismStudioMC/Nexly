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
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;
use RuntimeException;

class FenceGateBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$dir = $states[StateNames::MC_CARDINAL_DIRECTION];
		$inWall = $states[StateNames::IN_WALL_BIT];
		$open = $states[StateNames::OPEN_BIT];

		$expr = MolangHelper::and(
			MolangHelper::stateEquals(StateNames::MC_CARDINAL_DIRECTION, $dir),
			MolangHelper::stateEquals(StateNames::IN_WALL_BIT, $inWall),
			MolangHelper::stateEquals(StateNames::OPEN_BIT, $open)
		);
		return Permutation::create($expr)
			->addComponent(new CollisionBoxBlockComponent(!$open, [BoxCollision::FENCE_GATE()]))
			->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::FENCE_GATE()]))
			->addComponent(new TransformationBlockComponent(
				rotation: match ($dir) {
					StateValues::MC_CARDINAL_DIRECTION_NORTH => new Vector3(0, 0, 0),
					StateValues::MC_CARDINAL_DIRECTION_SOUTH => new Vector3(0, 180, 0),
					StateValues::MC_CARDINAL_DIRECTION_EAST => new Vector3(0, 270, 0),
					StateValues::MC_CARDINAL_DIRECTION_WEST => new Vector3(0, 90, 0),
					default => throw new RuntimeException("Invalid direction")
				},
				translation: new Vector3(0, $inWall ? -0.187 : 0, 0)
			));
	}
}
