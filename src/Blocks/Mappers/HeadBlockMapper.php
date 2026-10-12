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

class HeadBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$dir = $states[StateNames::FACING_DIRECTION];
		$rot = $states[StateNames::ROTATION];

		$translation = match ($dir) {
			2 => new Vector3(0, 0.25, 0.24),  // North
			3 => new Vector3(0, 0.25, -0.24), // South
			4 => new Vector3(0.24, 0.25, 0),  // West
			5 => new Vector3(-0.24, 0.25, 0), // East
			default => new Vector3(0, 0, 0)   // UP (Sol)
		};

		$expr = MolangHelper::and(
			MolangHelper::stateEquals(StateNames::FACING_DIRECTION, $dir),
			MolangHelper::stateEquals(StateNames::ROTATION, $rot)
		);
		return Permutation::create($expr)
			->addComponent(new CollisionBoxBlockComponent(true, [BoxCollision::MOBHEAD()]))
			->addComponent(new SelectionBoxBlockComponent(true, [BoxCollision::MOBHEAD()]))
			->addComponent(new TransformationBlockComponent(translation: $translation));
	}
}
