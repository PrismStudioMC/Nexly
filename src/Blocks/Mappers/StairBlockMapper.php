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
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\BoxCollision;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;
use RuntimeException;

class StairBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$upsideDown = $states[StateNames::UPSIDE_DOWN_BIT];
		$direction = $states[StateNames::DIRECTION];
		$corner = $states[StateNames::MC_CORNER];

		$collisions = [new BoxCollision(new Vector3(-8, 0, -8), new Vector3(16, 8, 16))];

		$effOuterLeft = $upsideDown ? ($corner === StateValues::MC_CORNER_OUTER_RIGHT) : ($corner === StateValues::MC_CORNER_OUTER_LEFT);
		$effOuterRight = $upsideDown ? ($corner === StateValues::MC_CORNER_OUTER_LEFT) : ($corner === StateValues::MC_CORNER_OUTER_RIGHT);
		$effInnerLeft = $upsideDown ? ($corner === StateValues::MC_CORNER_INNER_RIGHT) : ($corner === StateValues::MC_CORNER_INNER_LEFT);
		$effInnerRight = $upsideDown ? ($corner === StateValues::MC_CORNER_INNER_LEFT) : ($corner === StateValues::MC_CORNER_INNER_RIGHT);

		if (!$effOuterLeft) {
			$collisions[] = new BoxCollision(new Vector3(-8, 8, -8), new Vector3(8, 8, 8)); // top_nw
		}
		if (!$effOuterRight) {
			$collisions[] = new BoxCollision(new Vector3(0, 8, -8), new Vector3(8, 8, 8)); // top_ne
		}
		if ($effInnerRight) {
			$collisions[] = new BoxCollision(new Vector3(-8, 8, 0), new Vector3(8, 8, 8)); // top_sw
		}
		if ($effInnerLeft) {
			$collisions[] = new BoxCollision(new Vector3(0, 8, 0), new Vector3(8, 8, 8)); // top_se
		}

		$expr = MolangHelper::and(
			MolangHelper::stateEquals(StateNames::UPSIDE_DOWN_BIT, $upsideDown),
			MolangHelper::stateEquals(StateNames::DIRECTION, $direction),
			MolangHelper::stateEquals(StateNames::MC_CORNER, $corner)
		);
		return Permutation::create($expr)
			->addComponent(new CollisionBoxBlockComponent(true, $collisions))
			->addComponent(new TransformationBlockComponent(
				rotation: new Vector3($upsideDown ? 180 : 0, match ($direction) {
					!$upsideDown ? 0 : 1 => 270,    // East
					!$upsideDown ? 1 : 0 => 90,     // West
					!$upsideDown ? 2 : 3 => 180,    // South
					!$upsideDown ? 3 : 2 => 0,      // North
					default => throw new RuntimeException("Invalid direction"),
				}, 0),
			));
	}
}
