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
use pocketmine\math\Vector3;
use RuntimeException;

class LadderBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$dir = $states[StateNames::FACING_DIRECTION];

		$rotation = match ($dir) {
			2 => new Vector3(0, 0, 0),
			3 => new Vector3(0, 180, 0),
			4 => new Vector3(0, 90, 0),
			5 => new Vector3(0, 270, 0),
			default => throw new RuntimeException("Invalid direction")
		};

		$expr = MolangHelper::stateEquals(StateNames::FACING_DIRECTION, $dir);
		return Permutation::create($expr)
			->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::LADDER()]))
			->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::LADDER()]))
			->addComponent(new TransformationBlockComponent(rotation: $rotation));
	}
}
