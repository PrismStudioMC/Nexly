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
use Nexly\Blocks\Components\MaterialInstancesBlockComponent;
use Nexly\Blocks\Components\TransformationBlockComponent;
use Nexly\Blocks\Components\Types\Material;
use Nexly\Blocks\Components\Types\RenderMethod;
use Nexly\Blocks\Components\Types\Target;
use Nexly\Blocks\Permutations\Permutation;
use Nexly\Helpers\MolangHelper;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\BlockStateNames as StateNames;
use pocketmine\data\bedrock\block\BlockStateStringValues as StateValues;
use pocketmine\math\Vector3;
use RuntimeException;

class WoodBlockMapper extends BlockMapper
{
	public function map(Builder $builder, Block $block, array $states) : Permutation
	{
		$axis = $states[StateNames::PILLAR_AXIS];

		return Permutation::create(MolangHelper::stateEquals(StateNames::PILLAR_AXIS, $axis))
			->addComponent(new TransformationBlockComponent(rotation: match ($axis) {
				StateValues::PILLAR_AXIS_X => new Vector3(0, 0, 90),
				StateValues::PILLAR_AXIS_Y => new Vector3(0, 0, 0),
				StateValues::PILLAR_AXIS_Z => new Vector3(90, 0, 0),
				default => throw new RuntimeException("Invalid axis"),
			}))
			->addComponent(new MaterialInstancesBlockComponent([
				new Material(
					texture: $builder->getName() . "_top",
					target: Target::UP,
					renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
				),
				new Material(
					texture: $builder->getName() . "_top",
					target: Target::DOWN,
					renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
				),
				new Material(
					texture: $builder->getName() . "_side",
					target: Target::ALL,
					renderMethod: RenderMethod::ALPHA_TEST_SINGLE_SIDED
				)
			]));
	}
}
